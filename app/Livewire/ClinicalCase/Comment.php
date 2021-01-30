<?php

namespace App\Livewire\ClinicalCase;

use App\Models\ClinicalCaseComment;
use App\Queries\GetClinicalCaseChildrenCommentsQuery;
use App\Services\Features\CommentsFeature;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Redirector;

class Comment extends Component
{
    public int $clinicalCaseId;
    public int $commentId;
    public string $userName;
    public string $userImage;
    public string $comment;
    public Carbon $date;
    public int $childCommentsCount;
    public ?int $parentCommentId = null;

    public Collection $replies;
    public int $repliesPage;
    public int $repliesPerPage;
    public int $repliesTotal;
    public bool $hasMorePages = true;

    protected $listeners = [
        'reply-created' => 'replyCreated',
    ];

    public function mount(
        CommentsFeature $commentsFeature
    ): void {
        $this->repliesPage = 1;
        $this->repliesPerPage = $commentsFeature->repliesPerPage();
    }

    public function render(): View
    {
        return view('livewire.clinical-case.comment');
    }

    public function delete(): RedirectResponse | Redirector
    {
        $comment = ClinicalCaseComment::find($this->commentId);

        $comment->children->map->delete();
        $comment->delete();

        return response()->redirectTo($comment->clinicalCase->showUrl());
    }

    public function loadReplies(): void
    {
        $paginator = (new GetClinicalCaseChildrenCommentsQuery(
            $this->commentId
        ))->paginate($this->repliesPage, $this->repliesPerPage);

        $this->repliesPage++;
        $this->repliesTotal = $paginator->total();
        $this->hasMorePages = $paginator->hasMorePages();
        $this->childCommentsCount = $this->repliesTotal;

        $this->replies = isset($this->replies)
            ? $this->replies->push(...$paginator->all())
            : $paginator->getCollection();
    }

    public function replyCreated(int $replyId, int $parentCommentId): void
    {
        if ($parentCommentId !== $this->commentId) {
            return;
        }

        $this->repliesPage = 1;
        unset($this->replies);

        $this->loadReplies();

        $this->emitTo('clinical-case.comments-list', 'add-comment-to-counter');
        $this->emitTo('clinical-case.comments-list', 'scroll-to-comment', $replyId);
    }
}
