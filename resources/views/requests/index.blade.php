@extends('layouts.app')

@section('title', 'Farmer Requests')

@section('content')
<div class="space-y-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Supply Requests</h2>
            <p class="text-sm text-gray-500">Request seeds, fertilizers, tools, and equipment from administration</p>
        </div>
        @if(Auth::user()->isFarmer())
            <a href="{{ route('requests.create') }}" class="bg-agro-green text-white px-6 py-2.5 rounded-xl font-bold hover:bg-agro-green/90 transition shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Request
            </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 hide-scrollbar">
        @php $status = request('status', 'pending'); @endphp
        <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="px-4 py-2 {{ $status === 'pending' ? 'bg-agro-green text-white shadow-sm' : 'bg-white text-gray-600 hover:text-agro-green border border-gray-200' }} rounded-xl font-semibold text-sm whitespace-nowrap transition">Pending</a>
        <a href="{{ route('requests.index', ['status' => 'approved']) }}" class="px-4 py-2 {{ $status === 'approved' ? 'bg-agro-green text-white shadow-sm' : 'bg-white text-gray-600 hover:text-agro-green border border-gray-200' }} rounded-xl font-semibold text-sm whitespace-nowrap transition">Approved</a>
        <a href="{{ route('requests.index', ['status' => 'delivered']) }}" class="px-4 py-2 {{ $status === 'delivered' ? 'bg-agro-green text-white shadow-sm' : 'bg-white text-gray-600 hover:text-agro-green border border-gray-200' }} rounded-xl font-semibold text-sm whitespace-nowrap transition">Delivered</a>
        <a href="{{ route('requests.index', ['status' => 'all']) }}" class="px-4 py-2 {{ $status === 'all' ? 'bg-agro-green text-white shadow-sm' : 'bg-white text-gray-600 hover:text-agro-green border border-gray-200' }} rounded-xl font-semibold text-sm whitespace-nowrap transition">All Requests</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        @if(Auth::user()->isAdmin())
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Farmer</th>
                        @endif
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Item Name</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Requested At</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50/50 transition">
                            @if(Auth::user()->isAdmin())
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-agro-green/10 flex items-center justify-center text-[10px] text-agro-green font-bold">
                                            {{ substr($req->farmer->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-medium text-gray-700">{{ $req->farmer->name }}</span>
                                    </div>
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-gray-100 rounded text-[10px] font-bold text-gray-600 uppercase tracking-wider">
                                    {{ $req->request_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $req->item_name }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-600">{{ $req->quantity }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-status-warning/10 text-status-warning',
                                        'approved' => 'bg-blue-100 text-blue-600',
                                        'rejected' => 'bg-status-danger/10 text-status-danger',
                                        'delivered' => 'bg-status-success/10 text-status-success',
                                    ];
                                    $class = $statusClasses[$req->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="px-2 py-1 {{ $class }} rounded text-[10px] font-bold uppercase tracking-wider">
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">{{ $req->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                @if(Auth::user()->isAdmin() && $req->status === 'pending')
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" class="px-4 py-1.5 bg-agro-green text-white rounded-lg text-[10px] font-bold hover:bg-agro-green/90 transition shadow-sm uppercase tracking-wider">
                                            Respond
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 p-4 space-y-4">
                                            <form action="{{ route('requests.respond', $req) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 text-left">Admin Response</label>
                                                <textarea name="admin_response" rows="3" class="w-full text-xs rounded-xl border-gray-100 mb-3" placeholder="Notes..."></textarea>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button type="submit" name="status" value="approved" class="py-1.5 bg-blue-600 text-white rounded-lg text-[10px] font-bold uppercase hover:bg-blue-700 transition">Approve</button>
                                                    <button type="submit" name="status" value="rejected" class="py-1.5 bg-status-danger text-white rounded-lg text-[10px] font-bold uppercase hover:bg-status-danger/90 transition">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @elseif(Auth::user()->isAdmin() && $req->status === 'approved')
                                    <form action="{{ route('requests.respond', $req) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="delivered">
                                        <button type="submit" class="px-4 py-1.5 bg-status-success text-white rounded-lg text-[10px] font-bold hover:bg-status-success/90 transition shadow-sm uppercase tracking-wider">
                                            Mark Delivered
                                        </button>
                                    </form>
                                @elseif($req->admin_response)
                                    <button x-data @click="alert('{{ $req->admin_response }}')" class="p-2 text-gray-400 hover:text-agro-green transition" title="View Response">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isAdmin() ? 7 : 6 }}" class="px-6 py-20 text-center">
                                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-gray-500 font-medium">No requests found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-8">
        {{ $requests->links() }}
    </div>
</div>
@endsection
