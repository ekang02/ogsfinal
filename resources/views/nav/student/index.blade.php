<li class="nav-item {{ Request::is('student') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('/student')}}">
	  <i class="fas fa-fw fa-home"></i>
	  <span>My Grades</span>
	</a>
</li>
<!-- Divider -->
<!--
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('student/profile') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('student/profile')}}">
	  <i class="fas fa-fw fa-user-cog"></i>
	  <span>My Profile</span>
	</a>
</li>
-->