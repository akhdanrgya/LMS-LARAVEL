<div class="fixed top-0 left-0 h-screen w-[198px] bg-white flex flex-col gap-7 px-6 py-2.5 overflow-y-auto z-50">
  <img src="/images/sidebar-logo.png" class="self-stretch flex-grow-0 flex-shrink-0 h-[73.94px] object-none" />

  <p class="text-xs text-[#4c5a73]">OVERVIEW</p>

  @auth
    {{-- Home semua role bisa lihat --}}
    @if(auth()->user()->role == 'student')
    <a href="{{ route('student.dashboard') }}"
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-home text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Home</p>
    </a>
    @endif

    @if(auth()->user()->role == 'student')
    <a href="{{ route('student.dashboard', auth()->user()) }}"
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-chart-line text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Overview</p>
    </a>
    <a href="{{ route('student.my-courses.index', auth()->user()) }}"
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-chalkboard-user text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">My Courses</p>
    </a>
    <a href=""
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-chalkboard text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">All Courses</p>
    </a>
    @endif

    @if(auth()->user()->role == 'mentor')
    <a href="{{ route('mentor.dashboard')}}"
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-person-chalkboard text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Mentor Dashboard</p>
    </a>
    <a href="{{route('mentor.courses.index')}}"
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-book text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Manage Courses</p>
    </a>
    <a href=""
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-file-alt text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Manage Materials</p>
    </a>
    @endif

    @if(auth()->user()->role == "admin")
    <a href="{{ route('admin.dashboard')}}"
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-user-tie text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Admin Dashboard</p>
    </a>
    <p class="text-xs text-[#4c5a73]">ADMIN MENU</p>
    <a href="{{route('admin.users.index')}}"
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-users text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">User Management</p>
    </a>
    <a href="{{route('admin.courses.index')}}"
    class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-chalkboard text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Course Management</p>
    </a>
    @endif

  @endauth

  <p class="text-xs text-[#4c5a73] mt-4">FRIENDS</p>

  <a href="#" class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-user-ninja text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Martha</p>
  </a>

  <a href="#" class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4f6]">
    <i class="fas fa-user-astronaut text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Adan</p>
  </a>

  <a href="#" class="flex items-center gap-2.5 cursor-pointer px-2 py-1 rounded hover:bg-[#f3f4a73]">
    <i class="fas fa-skull text-[#4c5a73] w-4"></i>
    <p class="text-sm text-[#4c5a73]">Nca</p>
  </a>

  <div class="mt-auto pt-4 border-t border-gray-300"> {{-- mt-auto buat dorong ke bawah kalo sidebar flex column --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf {{-- Jangan lupa CSRF token! --}}
      <button type="submit"
        class="flex items-center gap-2.5 w-full cursor-pointer px-2 py-2 rounded text-sm text-red-600 hover:bg-red-100 hover:text-red-700">
        <i class="fas fa-sign-out-alt text-red-600 w-4"></i>
        <p>Logout</p>
      </button>
    </form>
  </div>
</div>