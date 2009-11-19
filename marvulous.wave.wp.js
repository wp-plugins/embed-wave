if(marvulous === undefined)
{
	var marvulous = {};
}
if(marvulous.wave == undefined)
{
	marvulous.wave = {};
}
marvulous.wave.wp = {
	_id_format : {},
	_WavePanel : {},
	_embedded : {},
	detect : function(){
		jQuery('.wave-panel').each(marvulous.wave.wp._embed);
	},
	_embed : function(i){
		if(this.id == '')
		{
			return;
		}
		else if(marvulous.wave.wp._embedded[this.id] == undefined)
		{
			var classes = this.className.split(' ');
			var format = false;
			for(var i in classes)
			{
				if(marvulous.wave.wp._id_format[classes[i]] != undefined)
				{
					format = marvulous.wave.wp._id_format[classes[i]];
					break;
				}
			}
			if(format == false)
			{
				return;
			}
			else
			{
				var wave = new WavePanel(marvulous.wave.wp._WavePanel[classes[i]]);
				wave.setUIConfig('white','black','Arial','13px');
				var id = this.id;
				if(id.substr(0,12) == 'wp_sidebar::')
				{
					id = id.substr(12);
				}
				wave.loadWave(format.replace(/_idgoeshere_/,id));
				wave.init(this);
				marvulous.wave.wp._embedded[this.id] = true;
				jQuery('.wave-panel .alt-content').remove();
			}
		}
	}
}