<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <span class="brand-text font-weight-light">POS Mini</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="/product" class="nav-link ">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Product
              </p>
            </a>
          </li>

          <?php if($access->data->group_id == '2'){ ?>
          <li class="nav-item ">
            <a href="/user" class="nav-link ">
              <i class="nav-icon fas fa-users"></i>
              <p>
                User
              </p>
            </a>
          </li>
          <?php } ?>

          <li class="nav-item">
            <a href="/auth/logout"  class="nav-link">
              <i class="nav-icon fas fa-lock"></i>
              <p>
                Log out
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

<div id="spin-box" style="display: none;">
	<div class="sk-chase sk-center" >
	  <div class="sk-chase-dot"></div>
	  <div class="sk-chase-dot"></div>
	  <div class="sk-chase-dot"></div>
	  <div class="sk-chase-dot"></div>
	  <div class="sk-chase-dot"></div>
	  <div class="sk-chase-dot"></div>
	</div>
</div>

<style type="text/css">
	#spin-box {
	    position:fixed;
	    width:100%;
	    left:0;right:0;top:0;bottom:0;
	    background-color: #308d9eb0;
	    z-index:9999;
	}
	.sk-chase {
	    margin-top: 50vh;
	}
</style>

<script type="text/javascript">


</script>