 <!-- Menu -->

 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo">
         <a href="index.html" class="app-brand-link">
             <span class="app-brand-text demo menu-text fw-bolder ms-2"><img src="<?= base_url('public/assets/img/sistema/logo_iexe.webp') ?>" width="190px"></span>
         </a>

         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
             <i class="bx bx-chevron-left bx-sm align-middle"></i>
         </a>
     </div>

     <div class="menu-inner-shadow"></div>

     <ul class="menu-inner py-1">

         <!-- Layouts -->
         <li class="menu-header small text-uppercase">
             <span class="menu-header-text">Administracion</span>
         </li>
         <li class="menu-item">
             <a href="<?= base_url('usuarios/lista') ?>" class="menu-link">
                 <i class='menu-icon bx bx-user'></i>
                 <div data-i18n="Accordion">Usuarios</div>
             </a>
         </li>
         
         <li class="menu-header small text-uppercase">
             <span class="menu-header-text">Proveedores</span>
         </li>

         <li class="menu-item">
             <a href="<?= base_url('proveedores/lista') ?>" class="menu-link">
                 <i class='bx bx-street-view'></i>
                 <div data-i18n="Buttons">Lista</div>
             </a>
         </li>
         <li class="menu-header small text-uppercase">
             <span class="menu-header-text">Inventario</span>
         </li>
         <li class="menu-item">
             <a href="<?= base_url('inventario/lista') ?>" class="menu-link">
                 <i class='menu-icon bx bx-user'></i>
                 <div data-i18n="Accordion">General</div>
             </a>
         </li>

         <li class="menu-header small text-uppercase">
             <span class="menu-header-text">Requisiciones</span>
         </li>

         <li class="menu-item">
             <a href="<?= base_url('requisiciones/lista') ?>" class="menu-link">
                 <i class='bx bx-street-view'></i>
                 <div data-i18n="Buttons">Lista</div>
             </a>
         </li>
     </ul>
 </aside>
 <!-- / Menu -->