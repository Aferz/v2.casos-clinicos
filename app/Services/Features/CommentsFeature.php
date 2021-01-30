<?php

namespace App\Services\Features;

class CommentsFeature extends Feature
{
    protected int $defaultCommentsPerPage = 15;
    protected int $defaultRepliesPerPage = 15;
    protected int $defaultSecondsBetweenComments = 60 * 5;

    public function commentsPerPage(): int
    {
        return $this->config['comments_per_page'] ?? $this->defaultCommentsPerPage;
    }

    public function repliesPerPage(): int
    {
        return $this->config['replies_per_page'] ?? $this->defaultRepliesPerPage;
    }

    public function secondsBetweenComments(): int
    {
        return $this->config['seconds_between_comments'] ?? $this->defaultSecondsBetweenComments;
    }
}
