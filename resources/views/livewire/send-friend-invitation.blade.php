<div class="m-4 p-6 max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <h2 class="text-lg font-semibold mb-4">Invite a Friend</h2>

    @if (session()->has('success'))
        <div class="p-2 mb-4 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-2 mb-4 bg-red-200 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    <input 
        type="email" 
        wire:model="recipientEmail" 
        placeholder="Enter friend's email" 
        class="w-full p-2 border rounded mb-4"
    >

    @error('recipientEmail')
        <div class="text-red-500 mb-4">{{ $message }}</div>
    @enderror

    <button 
        wire:click="sendInvitation" 
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
    >
        Send Invitation
    </button>
</div>
