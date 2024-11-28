<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{block name="title"}Default Title{/block}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/assets/css/adminlte.css">
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
         {include file="partials/sidebar.tpl"}
        <main class="app-main">
            <div class="app-content-header">
               
                <div class="container-fluid mt-2 mb-4">
                    <h2 class="mb-1 text-bold text-color-teal">Healthcare</h2>
                    <h2 class="mb-0 text-bold ">Management System</h2>
                </div>
                
            </div>
            {if $page == 'list'}
            <div class="app-content">
              <div class="container-fluid">
                  <div class="row">
            <div class="col-md-8">
                {block name="content"} {/block}
                 {include file="partials/cards-row.tpl"}
            </div>
            <div class="col-md-4">
                {include file="partials/summary-cards.tpl"}
                {include file="partials/available-doctors.tpl"}
            </div>
            {else}
              {block name="content"} {/block}
            {/if}


           
        </div>
    </div>
</div>
            
           
        </main>
       
    </div>
</body>
</html>
