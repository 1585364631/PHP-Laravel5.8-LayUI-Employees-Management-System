(function($, main) {
  var id = 0;
  main.uploader = function(opt) {
    opt = $.extend({
      obj: null,
      url: '',
      name: '',
      id: id++,
      success: function() {},
      error: function() {},
      finish: function() {},
      accept:  {},
      formData: {}
    }, opt);
    Uploader.create(opt);
  };
  function Uploader(opt) {
    $.extend(this, opt);
    var picker_id = this.name + '_picker_' + this.id;
    this.obj.find('.webuploader-file-picker').attr('id', picker_id);
    this.uploader = WebUploader.create({
      auto: true,             // 选完文件后，是否自动上传
      server: this.url,       // 文件接收服务端
      pick: '#' + picker_id,  // 选择文件的按钮
      fileVal: this.name,     // 上传的name
      duplicate: true,        // 允许重复上传同一张图片
      accept: this.accept,    // 只允许选择图片文件
      formData: this.formData // 附加数据
    });
    var that = this;
    var status = this.obj.find('.webuploader-status');
    var statusItems = {};
    this.uploader.on('uploadBeforeSend', function(obj, data, headers) {
      statusItems[obj.file.id] = Status.create(status);
      statusItems[obj.file.id].name.text(obj.file.name);
      headers['X-CSRF-TOKEN'] = main.token;
    });
    this.uploader.on('uploadProgress', function (file, percentage) {
      per = parseInt(percentage * 100) + '%';
      statusItems[file.id].progressBar.css('width', per);
      statusItems[file.id].progressPer.html(per);
    });
    this.uploader.on('uploadComplete', function (file) {
      statusItems[file.id].obj.fadeOut('fast', function() {
        $(this).remove();
      })
    });
    this.uploader.on('uploadSuccess', function (file, response) {
      if (response.code === 0) {
        main.toastr.error(file.name + ' ' + response.msg);
      }
      that.success(file, response);
    });
    this.uploader.on('uploadFinished', function() {
      that.finish();
    }),
    this.uploader.on('uploadError', function (file, reason) {
      main.toastr.error(file.name + ' 上传失败，服务器异常。');
      that.error(file, reason);
    });
  }
  Uploader.create = function(opt) {
    return new Uploader(opt);
  };
  function Status(obj) {
    this.obj = $('<div class="webuploader-status-item"></div>').appendTo(obj);
    this.name = $('<span class="webuploader-status-name"></span>').appendTo(this.obj);
    this.progress = $('<span class="webuploader-status-progress"></span>').appendTo(this.obj);
    this.progressBar = $('<i></i>').appendTo(this.progress);
    this.progressPer = $('<span class="webuploader-status-per">0%</span>').appendTo(this.obj);
  }
  Status.create = function(obj) {
    return new Status(obj);
  };
})(jQuery, main);