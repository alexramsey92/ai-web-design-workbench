@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
## Livewire

- Use the ___SINGLE_BACKTICK___search-docs___SINGLE_BACKTICK___ tool to find exact version-specific documentation for how to write Livewire and Livewire tests.
- Use the ___SINGLE_BACKTICK___{{ $assist->artisanCommand('make:livewire [Posts\\CreatePost]') }}___SINGLE_BACKTICK___ Artisan command to create new components.
- State should live on the server, with the UI reflecting it.
- All Livewire requests hit the Laravel backend; they're like regular HTTP requests. Always validate form data and run authorization checks in Livewire actions.

## Livewire Best Practices
- Livewire components require a single root element.
- Use ___SINGLE_BACKTICK___wire:loading___SINGLE_BACKTICK___ and ___SINGLE_BACKTICK___wire:dirty___SINGLE_BACKTICK___ for delightful loading states.
- Add ___SINGLE_BACKTICK___wire:key___SINGLE_BACKTICK___ in loops:
@verbatim
    ___SINGLE_BACKTICK______SINGLE_BACKTICK______SINGLE_BACKTICK___blade
    @foreach ($items as $item)
        <div wire:key="item-{{ $item->id }}">
            {{ $item->name }}
        </div>
    @endforeach
    ___SINGLE_BACKTICK______SINGLE_BACKTICK______SINGLE_BACKTICK___
@endverbatim
- Prefer lifecycle hooks like ___SINGLE_BACKTICK___mount()___SINGLE_BACKTICK___, ___SINGLE_BACKTICK___updatedFoo()___SINGLE_BACKTICK___ for initialization and reactive side effects:
@verbatim
<code-snippet name="Lifecycle Hook Examples" lang="php">
    public function mount(User $user) { $this->user = $user; }
    public function updatedSearch() { $this->resetPage(); }
</code-snippet>
@endverbatim

## Testing Livewire
@verbatim
<code-snippet name="Example Livewire Component Test" lang="php">
    Livewire::test(Counter::class)
        ->assertSet('count', 0)
        ->call('increment')
        ->assertSet('count', 1)
        ->assertSee(1)
        ->assertStatus(200);
</code-snippet>
@endverbatim
@verbatim
<code-snippet name="Testing Livewire Component Exists on Page" lang="php">
    $this->get('/posts/create')
    ->assertSeeLivewire(CreatePost::class);
</code-snippet>
@endverbatim
