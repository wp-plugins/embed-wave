if(marvulous === undefined)
{
	var marvulous = {};
}
if(marvulous.wave == undefined)
{
	marvulous.wave = {};
}
marvulous.wave.wp = {
	_id_format : {
		'google-wave' : 'googlewave.com!w+_idgoeshere_'
	},
	_WavePanel : {
		'google-wave' : 'https://wave.google.com/wave/'
	},
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
				wave.loadWave(format.replace(/_idgoeshere_/,this.id));
				wave.init(this);
				marvulous.wave.wp._embedded[this.id] = true;
			}
		}
	}
}
jQuery(document).ready(marvulous.wave.wp.detect);