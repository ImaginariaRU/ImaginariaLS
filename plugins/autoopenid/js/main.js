var ls = ls || {};
ls.plugin = ls.plugin || {};

ls.plugin.autoopenid =( function ($) {

	var that=this;

	this.goServiceLogin = function(service) {
		ls.ajax('autoopenid/login-oauth',{ service: service, referal: window.location.href },function(res){
			if (!res.bStateError) {
				window.location.href=res.sUrl;
			} else {
				ls.msg.error(null,'Регистрация через '+service+' временно не доступна.');
			}
		}.bind(this));
	};

	this.removeService = function(type,id) {
		ls.ajax('autoopenid/service-remove',{ type: type, id: id },function(res){
			if (!res.bStateError) {
				$('#autoopenid-service-connect-item-'+res.id).remove();
			} else {
				ls.msg.error(null,res.sMsg);
			}
		}.bind(this));
	};

	this.showFormData = function() {
		$('#aoid-form-mail').hide();
		$('#aoid-form-data').show();
		$('#aoid-toggle-data').addClass('active');
		$('#aoid-toggle-mail').removeClass('active');
	};

	this.showFormMail = function() {
		$('#aoid-form-mail').show();
		$('#aoid-form-data').hide();
		$('#aoid-toggle-data').removeClass('active');
		$('#aoid-toggle-mail').addClass('active');
	};

	this.toggleFieldMail = function() {
		$('#aoid-field-mail').toggle();
	};

	$(function(){
		$(document).on('click', '.js-autoopenid-auth', function (e) {
			that.goServiceLogin($(this).data('service'));
			return false;
		});
		$(document).on('click', '.js-autoopenid-remove', function (e) {
			that.removeService($(this).data('serviceType'),$(this).attr('data-service-id'));
			return false;
		});
	}.bind(this));

	return this;
}).call(ls.plugin.autoopenid || {},jQuery);