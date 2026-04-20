<x-app-layout>
    <div class="mt-[50px] px-4">
        <a href="{{ route('admin.index') }}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[30px] hover:text-[#E84400] hover:underline transition">← Административная панель</a>
        <div class="mt-8 mb-4">
            <form method="GET" action="{{ route('admin.users') }}" class="flex gap-2 max-w-md">
                <input type="text" name="search" placeholder="Поиск по имени, фамилии или отчеству"
                    value="{{ request('search') }}"
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded">
                <button type="submit" class="bg-[#E84400] hover:bg-[#df6937] text-white px-4 py-2 rounded">Поиск</button>
                @if(request('search'))
                <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Сбросить</a>
                @endif
            </form>
        </div>
        <div class="mt-8 overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-[#E84400]">
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Имя</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Роль</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->name }} {{ $user->middlename }} {{ $user->lastname }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($user->role === 'admin')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-xl text-xs">Администратор</span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-xl text-xs">Пользователь</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Удалить пользователя {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Удалить</button>
                            </form>

                            <form action="{{ route('admin.users.toggleRole', $user) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                @if($user->role === 'admin')
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded">Забрать роль</button>
                                @else
                                <button type="submit" class="bg-[#E84400] hover:bg-[#F7733C] text-white px-3 py-1 rounded">Сделать админом</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(session('success'))
        <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mt-4 p-2 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
        @endif
    </div>
</x-app-layout>