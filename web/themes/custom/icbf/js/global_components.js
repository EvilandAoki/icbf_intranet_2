$ = jQuery;

(function ($, Drupal) {
  Drupal.behaviors.icbfTheme = {
    attach: function (context, settings) {
      var active_class = 'active';

      $('.views-bootstrap-tabs ul.nav-tabs >li >a').click(function() {
        var tabId = $(this).attr('href');
        var $parent = $(this).parents('.views-bootstrap-tabs');
        $(this).parent().addClass(active_class).siblings().removeClass(active_class);
        $parent.find('.tab-pane').removeClass(active_class);
        $parent.find(tabId).addClass(active_class);
      });
    }
  }
})(jQuery, Drupal);




//se añade funcionalkidad global para el uso de collapse
$(document).ready(function () {
  // Mostrar el panel al cargar correctamente usando Bootstrap

  $('[data-toggle="collapse"]').click(function (e) {
    const target = $(this).attr('href');
    const parent = $(this).data('parent');

    // Ocultar todos menos el actual
    $(parent).find('.collapse').not(target).collapse('hide');
    $(target).collapse('toggle');
  });

  $('.collapse').on('show.bs.collapse', function () {
    const id = $(this).attr('id');
    $(`[href="#${id}"]`).removeClass('collapsed').attr('aria-expanded', 'true');
  });

  $('.collapse').on('hide.bs.collapse', function () {
    const id = $(this).attr('id');
    $(`[href="#${id}"]`).addClass('collapsed').attr('aria-expanded', 'false');
  });
});