(function (window, $, toastr) {
  window.main = {
    token: '',					// 保存令牌
    toastr: toastr,
    init: function (opt) {
      $.extend(this, opt);		// 将传入的opt对象合并到自身对象中
      toastr.options.positionClass = 'toast-top-center';
      return this;
    },
    ajax: function (opt, success, error) {
      opt = (typeof opt === 'string') ? {url: opt} : opt;
      var that = this;
      var options = {
        success: function (data, status, xhr) {
          that.hideLoading();
          if (!data) {
            toastr.error('请求失败，请重试。');
          } else if (data.code === 0) {
            toastr.error(data.msg);
            error && error(data);
          } else {
            success && success(data);
          }
          opt.success && opt.success(data, status, xhr);
        },
        error: function (xhr, status, err) {
          that.hideLoading();
          toastr.error('请求失败，请重试。');
          opt.error && opt.error(xhr, status, err);
        }
      };
      that.showLoading();
      $.ajax($.extend({}, opt, options));
    },
    showLoading: function() {
      $('.main-loading').show();
    },
    hideLoading: function() {
      $('.main-loading').hide();
    },
    ajaxPost: function(opt, success, error) {
      opt = (typeof opt === 'string') ? {url: opt} : opt;
      var that = this;
      var callback = opt.success;
      opt.type = 'POST';
      opt.success = function(data, status, xhr) {
        if (data && data.code === 1) {
          toastr.success(data.msg);
        }
        callback && callback(data, status, xhr);
      };
      that.ajax(opt, success, error);
    },
    ajaxForm: function (selector, success, error) {
      var form = $(selector);
      var that = this;
      form.submit(function (e) {
        e.preventDefault();
        that.ajaxPost({
          url: form.attr('action'),
          data: new FormData(form.get(0)),
          contentType: false,
          processData: false
        }, success, error);
      });
    },
    layout: function() {
      $('.main-sidebar-collapse-btn').click(function() {
        $(this).parent().find('.nav').slideToggle(200);
        $(this).parent().toggleClass('main-sidebar-collapse').siblings().
            addClass('main-sidebar-collapse').find('.nav').slideUp(200);
        return false;
      });
    },
    menuActive: function(name) {
      var menu = $('.main-menu');
      menu.find('a').removeClass('active');
      menu.find('a[data-name=\'' + name + '\']').addClass('active');
      menu.find('a[data-name=\'' + name + '\']').parent().parent().show();
    }

  };
})(this, jQuery, toastr);
