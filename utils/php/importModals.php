<?php
function importModals()
{
  $trace = debug_backtrace();
  $path = explode("index.php", $trace[0]['file'])[0];
  $path .= 'modals.php';
  $modals = file_get_contents($path);
  $listModals = explode('<fieldset', $modals);
  array_shift($listModals);
  foreach ($listModals as $modalItem) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->loadHTML('<fieldset ' . $modalItem);
    $formElement = $dom->getElementsByTagName('fieldset')->item(0);
    $id = $formElement->getAttribute('id');

    $detail = $formElement->getAttribute('detail');
    $detailMode = !empty($detail) ?  "detail='{$detail}'" : '';

    $detailRef = $formElement->getAttribute('dref');
    $dref = !empty($detailRef) ?  "dref='{$detailRef}'" : '';

    $crud = $formElement->getAttribute('crud') ?: '';
    $crudForm = $crud ? "<form  enctype='multipart/form-data' method='POST' class='modal-content'>" : "<div class='modal-content'>";
    $crudFormClose = $crud ? '</form>' : '</div>';
    $crudFormButton = $crud ? 'submit' : 'button';
    $size = $formElement->getAttribute('size') ?: 'xl';

    $title = utf8_decode($formElement->getAttribute('title')) ?: '';
    $scroll = $formElement->getAttribute('scroll') ?: false;
    $styleBody = ($scroll) ? 'style="max-height: calc(100vh - 200px); overflow-y: auto;"' : '';

    $modal = "<div class='modal fade' id='{$id}' {$detailMode} {$dref} tabindex='-1'
             data-bs-backdrop='static' data-bs-keyboard='false'
              role='dialog' aria-labelledby='{$id}' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-scrollable modal-dialog-centered modal-{$size}' role='document'>
                  {$crudForm} 
                    <div class='modal-header p-2'>
                      <h5 class='modal-title'>
                        {$title}
                      </h5>
                      <button type='button' class='btn-close me-1' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div {$styleBody} class='modal-body'>
                      <fieldset {$modalItem}
                    </div>
                    <div class='modal-footer p-1 m-1'>
                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                      <button type='{$crudFormButton}' class='btn btn-primary'>Guardar</button>
                    </div>
                  {$crudFormClose}
                </div>
              </div>";
    print $modal;
  }
}

function importExtModal($path)
{
  $modals = file_get_contents($path);
  $listModals = explode('<fieldset', $modals);
  array_shift($listModals);
  foreach ($listModals as $modalItem) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->loadHTML('<fieldset ' . $modalItem);
    $formElement = $dom->getElementsByTagName('fieldset')->item(0);
    $id = $formElement->getAttribute('id');

    $detail = $formElement->getAttribute('detail');
    $detailMode = !empty($detail) ?  "detail='{$detail}'" : '';

    $detailRef = $formElement->getAttribute('dref');
    $dref = !empty($detailRef) ?  "dref='{$detailRef}'" : '';

    $crud = $formElement->getAttribute('crud') ?: '';
    $crudForm = $crud ? "<form  enctype='multipart/form-data' method='POST' class='modal-content'>" : "<div class='modal-content'>";
    $crudFormClose = $crud ? '</form>' : '</div>';
    $crudFormButton = $crud ? 'submit' : 'button';
    $size = $formElement->getAttribute('size') ?: 'xl';

    $title = utf8_decode($formElement->getAttribute('title')) ?: '';
    $scroll = $formElement->getAttribute('scroll') ?: false;
    $styleBody = ($scroll) ? 'style="max-height: calc(100vh - 200px); overflow-y: auto;"' : '';

    $modal = "<div class='modal fade' id='{$id}' {$detailMode} {$dref} tabindex='-1'
             data-bs-backdrop='static' data-bs-keyboard='false'
              role='dialog' aria-labelledby='{$id}' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-scrollable modal-dialog-centered modal-{$size}' role='document'>
                  {$crudForm} 
                    <div class='modal-header p-2'>
                      <h5 class='modal-title'>
                        {$title}
                      </h5>
                      <button type='button' class='btn-close me-1' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div {$styleBody} class='modal-body'>
                      <fieldset {$modalItem}
                    </div>
                    <div class='modal-footer p-1 m-1'>
                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                      <button type='{$crudFormButton}' class='btn btn-primary'>Guardar</button>
                    </div>
                  {$crudFormClose}
                </div>
              </div>";
    print $modal;
  }
}
