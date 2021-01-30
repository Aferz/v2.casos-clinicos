<x-layout.home>
    <div class="w-full min-h-full p-4 md:p-8">
        <livewire:user.form
            :user-id="$user->id"
            :role="$user->isAdmin() ? 'admin' : 'coordinator'"
        />
    </div>
</x-layout.home>