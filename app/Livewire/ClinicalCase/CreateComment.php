<?php

namespace App\Livewire\ClinicalCase;

use App\Models\ClinicalCaseComment;
use App\Services\Features\CommentsFeature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class CreateComment extends Component
{
    public int $clinicalCaseId;
    public ?int $parentCommentId = null;

    public string $comment = '';
    public int $secondsBetweenComments;

    public function mount(
        CommentsFeature $commentsFeature
    ): void {
        $this->secondsBetweenComments = $commentsFeature->secondsBetweenComments();
    }

    public function render(): View
    {
        return view('livewire.clinical-case.create-comment');
    }

    public function createComment(): void
    {
        $this->ensureIsNotRateLimited();

        $this->validate([
            'comment' => 'required|min:10',
        ]);

        $newComment = ClinicalCaseComment::create([
            'clinical_case_id' => $this->clinicalCaseId,
            'user_id' => auth()->user()->id,
            'comment' => $this->comment,
            'parent_comment_id' => $this->parentCommentId,
        ]);

        RateLimiter::hit($this->throttleKey(), $this->secondsBetweenComments);

        $this->comment = '';

        if ($this->parentCommentId) {
            $this->emitTo('clinical-case.comment', 'reply-created', $newComment->id, $this->parentCommentId);
        } else {
            $this->emitTo('clinical-case.comments-list', 'comment-created', $newComment->id);
        }
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 1)) {
            $availableIn = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'limited' => trans_choice('You must wait :seconds seconds to comment again', $availableIn, [
                    'seconds' => $availableIn,
                ]),
            ]);
        }
    }

    protected function throttleKey(): string
    {
        return Str::lower('create-comment:'.auth()->user()->id);
    }
}
