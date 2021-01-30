<?php

namespace App\Livewire\ClinicalCase;

use App\Models\ClinicalCaseComment;
use App\Queries\GetClinicalCaseParentCommentsQuery;
use App\Services\Features\CommentsFeature;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class CommentsList extends Component
{
    public int $clinicalCaseId;
    public int $commentsCount;

    public Collection $comments;
    public int $page;
    public int $perPage;
    public bool $hasMorePages = true;
    public int $total;

    protected $listeners = [
        'comment-created' => 'commentCreated',
        'add-comment-to-counter' => 'addCommentToCounter',
        'scroll-to-comment' => 'scrollToComment',
    ];

    public function mount(
        CommentsFeature $commentsFeature
    ): void {
        $this->page = 1;
        $this->perPage = $commentsFeature->commentsPerPage();

        $this->loadComments();
    }

    public function render(): View
    {
        return view('livewire.clinical-case.comments-list');
    }

    public function loadComments(): void
    {
        $paginator = (new GetClinicalCaseParentCommentsQuery(
            $this->clinicalCaseId
        ))->paginate($this->page, $this->perPage);

        $this->page++;
        $this->total = $paginator->total();
        $this->hasMorePages = $paginator->hasMorePages();

        $this->comments = isset($this->comments)
            ? $this->comments->push(...$paginator->all())
            : $paginator->getCollection();
    }

    public function commentCreated(int $commentId): void
    {
        $comment = ClinicalCaseComment::find($commentId);
        $comment->child_comments_count = 0;

        $this->comments->prepend($comment);

        $this->addCommentToCounter();
        $this->scrollToComment($commentId);
    }

    public function addCommentToCounter(): void
    {
        $this->commentsCount++;
    }

    public function scrollToComment(int $commentId): void
    {
        $this->dispatchBrowserEvent('scrollIntoView', [
            'elementId' => "comment-{$commentId}",
        ]);
    }
}
