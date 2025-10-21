<x-filament::page>
    <x-filament-panels::header
        title="User Profile"
        description="Manage your account settings and update your profile information."
    />

    <form wire:submit="save">
        <div class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">
                    Profile Information
                </x-slot>
                <x-slot name="description">
                    Update your account's profile information and email address.
                </x-slot>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="text"
                            wire:model="form.name"
                            label="Full Name"
                            required
                        />
                    </x-filament::input.wrapper>
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="email"
                            wire:model="form.email"
                            label="Email Address"
                            required
                        />
                    </x-filament::input.wrapper>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    Update Password
                </x-slot>
                <x-slot name="description">
                    Ensure your account is using a long, random password to stay secure.
                </x-slot>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="password"
                            wire:model="form.current_password"
                            label="Current Password"
                            required
                        />
                    </x-filament::input.wrapper>
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="password"
                            wire:model="form.password"
                            label="New Password"
                            minlength="8"
                        />
                    </x-filament::input.wrapper>
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="password"
                            wire:model="form.password_confirmation"
                            label="Confirm New Password"
                            required
                        />
                    </x-filament::input.wrapper>
                </div>
            </x-filament::section>

            <div class="flex justify-end">
                <x-filament::button type="submit">
                    Save Changes
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament::page>
