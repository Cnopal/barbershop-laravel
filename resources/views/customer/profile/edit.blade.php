@extends('customer.sidebar')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="bg-white shadow-lg rounded-xl w-full max-w-2xl p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Profile</h1>

        @if($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-gray-700 font-medium mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Address</label>
                <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Profile Image</label>
                <input type="file" name="profile_image" class="w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">New Password</label>
                <input type="password" name="password" class="w-full border-gray-300 rounded-lg p-3">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg p-3">
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('customer.profile.show') }}" class="text-gray-500 hover:underline">Cancel</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium shadow transition">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
