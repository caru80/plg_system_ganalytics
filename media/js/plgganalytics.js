'use strict';

(function($){

	window.gaOptOut = {

		defaults : {
			stage			: 'body',
			css 			: {visible : 'visible', button : 'msg-close'},
			cookiename 		: 'gaoptout',
			realoadPage 	: true,
			messsage 		: ''
		},

		init : function( options )
		{
			if( options )
			{
				this.options = $.extend(true, {}, this.defaults, options);
			}
			else
			{
				this.options = this.defaults;
			}

			this.stage = $(this.options.stage);
			this.setOptoutLink();
		},

		setOptoutLink : function(){
			var self = this;

			$('[data-gaoptout]').on('click.gaoptout', function() {
				document.cookie = self.options.cookiename + "=1; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
				if(self.options.message != '')
				{
					self.showMessage();
				}
				else if(self.options.reloadPage)
				{
					window.location.reload();
				}
			});
		},

		/*
			Entfernt eine Nachricht von this.options.stage
		*/
		removeMessage : function(msg)
		{
			let self 	= this,
				removed = false; // Evtl. Chromes doppeltes Event-Abfeuern fixen

			$(msg).one('transitionend webkitTransitionEnd oTransitionEnd', function() {
				if(removed) return;
				$(this).remove();
				removed = true;
				if(self.options.reloadPage) {
					window.location.reload();
				}
			}).removeClass(this.options.css.visible);
		},

		setTrigger : function( msg )
		{
			let self 	= this,
				trigger = msg.find('.' + this.options.css.button);

			if(trigger.length)
			{
				trigger.data('app-message', msg).on('click.gaoptout', function(ev)
				{
					ev.preventDefault();
					let msg = trigger.data('app-message');
					self.removeMessage( msg );
					return false;
				});
			}
		},

		/*
			m{
				text : {text : html}
			}
		*/
		showMessage : function()
		{
			var msg = $(this.options.message);

			this.stage.append(msg);
			msg.get(0).offsetWidth; // Browser zwingen das Ding zu rendern, ganz wichtig!
			this.setTrigger(msg);
			msg.addClass(this.options.css.visible);
		}
	}
})(jQuery);
