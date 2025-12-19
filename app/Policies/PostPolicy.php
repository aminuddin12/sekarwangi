<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-posts');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-posts');
    }

    public function update(User $user, Post $post): bool
    {
        // Penulis boleh edit artikelnya sendiri
        if ($user->id === $post->author_id) {
            return true;
        }
        // Editor boleh edit artikel siapa saja
        return $user->hasPermissionTo('edit-others-posts');
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->id === $post->author_id) {
            return true;
        }
        return $user->hasPermissionTo('delete-others-posts');
    }

    public function publish(User $user): bool
    {
        // Contributor mungkin hanya bisa 'Draft', Editor yang bisa 'Publish'
        return $user->hasPermissionTo('publish-posts');
    }
}
