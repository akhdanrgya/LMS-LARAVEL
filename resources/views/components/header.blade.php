<div class="flex justify-between items-center w-full">

    @if(request()->is('admin'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-3xl">Admin Dashboard</p>
        </div>
    @elseif (request()->is('courses'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-3xl">Courses</p>
        </div>
    @elseif (request()->is('*/courses'))
        <div class="w-full max-w-[658px]">
            <p class="text-[#4c5a73] text-3xl">My Courses</p>
        </div>
    @else
        <div class="flex items-center gap-4 px-6 py-3 rounded-full bg-white border border-[#4c5a73] w-full max-w-[658px]">
            <i class="fa-solid fa-magnifying-glass text-[#4c5a73] text-xl"></i>
            <input type="text" placeholder="Search your course...."
                class="bg-transparent outline-none text-[#4c5a73] text-xl w-full" />
        </div>
    @endif
    <div class="relative" x-data="{ open: false, roleOpen: false }">
        <div class="flex items-center gap-4">
            <i class="fa-regular fa-bell text-[#4c5a73] text-xl"></i>
            <span class="text-xl text-[#4c5a73]">{{ Auth::user()->name }}</span>
            <i class="fa-solid fa-chevron-down text-[#4c5a73] text-xl cursor-pointer" @click="open = !open"></i>
        </div>

        <!-- dropdown -->
        <div x-show="open" @click.outside="open = false" x-cloak x-transition
            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
            <ul class="py-2 text-sm text-gray-700">
                <li>
                    <a href="/profile" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                        <i class="fa-solid fa-user text-[#4c5a73] text-base"></i>
                        Profile
                    </a>
                </li>
                <li>
                    <a href="/settings" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                        <i class="fa-solid fa-gear text-[#4c5a73] text-base"></i>
                        Settings
                    </a>
                </li>
                @if(auth()->user()->name === 'admin')
                    <li>
                        <div class="relative">
                            <button @click="roleOpen = !roleOpen"
                                class="w-full flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                                <i class="fa-solid fa-users text-[#4c5a73] text-base"></i>
                                Ganti Role
                                <i class="fa-solid fa-chevron-right ml-auto text-xs"></i>
                            </button>

                            <!-- Popup ganti role -->
                            <div x-show="roleOpen" @click.outside="roleOpen = false" x-cloak
                                class="absolute left-full top-0 ml-1 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                                <form method="POST" action="{{ route('admin.update-role') }}">
                                    @csrf
                                    <div class="space-y-2 p-2">
                                        <label class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                            <input type="radio" name="role" value="admin"
                                                @checked(auth()->user()->role === 'admin') class="rounded text-blue-600">
                                            Admin
                                        </label>
                                        <label class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                            <input type="radio" name="role" value="mentor"
                                                @checked(auth()->user()->role === 'mentor') class="rounded text-blue-600">
                                            Mentor
                                        </label>
                                        <label class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                            <input type="radio" name="role" value="student"
                                                @checked(auth()->user()->role === 'student') class="rounded text-blue-600">
                                            Student
                                        </label>
                                        <button type="submit"
                                            class="w-full text-left p-2 text-blue-600 hover:bg-blue-50 rounded">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 px-4 py-2 hover:bg-gray-100 text-red-600">
                            <i class="fa-solid fa-right-from-bracket text-red-600 text-base"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>

</div>