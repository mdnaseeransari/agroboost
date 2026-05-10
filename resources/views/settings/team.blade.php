@extends('settings.layout')

@section('settings_content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold font-poppins text-gray-900">Team Management</h2>
            <p class="text-sm text-gray-500 mt-1">Manage who has access to your farm data and their permission levels.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50/50 border-b border-gray-100 text-gray-500">
                <tr>
                    <th class="px-6 py-4 font-semibold">Team Member</th>
                    <th class="px-6 py-4 font-semibold">Role</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($teamMembers as $member)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-agro-gold/20 flex items-center justify-center text-agro-green font-bold">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $member->name }}</p>
                                <p class="text-xs text-gray-500">{{ $member->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 capitalize">
                            {{ $member->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <x-stat-badge type="healthy" label="Active" />
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($member->id !== Auth::id())
                        <div class="flex justify-end gap-2">
                            <button class="p-2 text-gray-400 hover:text-agro-gold rounded-lg transition" title="Edit Role">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-status-danger rounded-lg transition" title="Remove Member">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        @else
                        <span class="text-xs text-gray-400 italic">It's you</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
