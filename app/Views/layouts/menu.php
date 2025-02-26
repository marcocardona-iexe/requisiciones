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
         <!-- Dashboard -->
         <li class="menu-item">
             <a href="<?= base_url('dahsboard') ?>" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-home-circle"></i>
                 <div data-i18n="Analytics">Dashboard</div>
             </a>
         </li>

         <!-- Layouts -->

         <li class="menu-header small text-uppercase">
             <span class="menu-header-text">Requisiciones</span>
         </li>
         <li class="menu-item">
             <a href="<?= base_url('usuarios/lista') ?>" class="menu-link">
                 <i class='bx bx-user'></i>
                 <div data-i18n="Accordion">Lista</div>
             </a>
         </li>
         <li class="menu-item">
             <a href="<?= base_url('choferes/lista') ?>" class="menu-link">
                 <i class='bx bx-street-view'></i>
                 <div data-i18n="Buttons">Nueva requisicón</div>
             </a>
         </li>


     </ul>
 </aside>
 <!-- / Menu -->