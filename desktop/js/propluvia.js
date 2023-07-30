/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* Permet la réorganisation des commandes dans l'équipement */
$("#table_cmd").sortable({
  axis: "y",
  cursor: "move",
  items: ".cmd",
  placeholder: "ui-state-highlight",
  tolerance: "intersect",
  forcePlaceholderSize: true
})

 $('.eqLogicAttr[data-l1key=configuration][data-l2key=datasource]').on('change',function(){
    $('.datasource').hide();
    $('.datasource.'+$(this).value()).show();
});

/* Fonction permettant l'affichage des commandes dans l'équipement */
function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = { configuration: {} };
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
	tr += '<td>';
    	tr += '<span class="cmdAttr" data-l1key="id" title="' + init(_cmd.logicalId) + '"></span>';
    tr += '</td>';
    
   	tr += '<td>';
    tr += '<div class="input-group" style="width: 90%;">';
    	tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom}}">';
    	tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>';
    	tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>';
    tr += '</div>';
  	
  	if (_cmd.type == 'action' && _cmd.value != ''){
      tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" disabled style="margin-top:5px;width: calc(90% - 35px);display:none" title="{{Commande info liée}}">';
      tr += '<option value="">{{Aucune}}</option>';
      tr += '</select>';
    }
    tr += '</td>';

    tr += '<td>';
    //tr += '<span class="type" type="' + init(_cmd.type) + '" style="display:none;" data-l1key="type">' + jeedom.cmd.availableType() + '</span>'
    tr += '<span class=" cmdAttr type" type="' + init(_cmd.type) + '" data-l1key="type"  style="display:none;"></span>'
  	tr += '<span class=" cmdAttr subType" subType="' + init(_cmd.subType) + '" data-l1key="subType"></span>'
  	tr += '</td>';

  
  
  	tr += '<td>';
    tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/>{{Afficher}}</label> ';
    if (_cmd.subType == "binary") {
    	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized">{{Historiser}}</label> ';
    	tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label> ';
    }
  	else if (_cmd.subType == "numeric") {
	  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" checked/>{{Historiser}}</label> ';
    }
  	else if (_cmd.subType == "slider") {
        tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%; max-width: 60px;display:inline-block;margin-left: 10px;">';
        tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;max-width: 60px;display:inline-block;margin-left:2px;">';
        
    }
	tr += '</td>';
  	tr += '<td>';
    if (typeof jeeFrontEnd !== 'undefined' && jeeFrontEnd.jeedomVersion !== 'undefined') {
        var cmdCible_Name = "";
        if (_cmd.type == 'action' && _cmd.value != undefined){        
            
        }
      	tr += '<span class="cmdAttr" data-l1key="htmlstate">'+cmdCible_Name+'</span>';
        
    }
	tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
    }
  	if (_cmd.type == 'action'){        
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
    }
    tr += '</td>';
    tr += '</tr>';

    if (_cmd.type == 'info'){
        $('#table_infos tbody').append(tr)
        $('#table_infos tbody tr:last').setValues(_cmd, '.cmdAttr')
      	//$('#table_infos tbody tr:last').find('.cmdAttr[data-l1key=type],.cmdAttr[data-l1key=subType]').prop("disabled", true);
        //if (isset(_cmd.type)) $('#table_infos tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type))
        //jeedom.cmd.changeType($('#table_infos tbody tr:last'), init(_cmd.subType))
    }
    else{
     	$('#table_actions tbody').append(tr)
        const $tr = $('#table_actions tbody tr:last');
    	if(_cmd.value != null && _cmd.value != ''){
          	jeedom.eqLogic.buildSelectCmd({
                id: $('.eqLogicAttr[data-l1key=id]').value(),
                filter: { type: 'info' },
                error: function (error) {
                    $('#div_alert').showAlert({ message: error.message, level: 'danger' });
                },
                success: function (result) {
                    $tr.find('.cmdAttr[data-l1key=value]').append(result);//.show();
                    //jeedom.cmd.changeType($tr, init(_cmd.subType));
                    //$tr.find('.cmdAttr[data-l1key=type],.cmdAttr[data-l1key=subType]').prop("disabled", true);
                }
            })
          /////////
          	jeedom.cmd.getHumanCmdName({
                id: _cmd.value,
                success: function (data) {
                    cmdCible_Name = data.replace(/#/g, "").split("][").pop().replace(/]/g, "");
                  	var spanValue = '<span id="cmdCible" data-cmd_id="' + _cmd.value + '">'+cmdCible_Name+'</span>'
                    $tr.find('.cmdAttr[data-l1key=htmlstate]').html(spanValue);
                }
            })
          	        
        }
      	$tr.setValues(_cmd, '.cmdAttr');
            
      	/**/
      
      /*jeedom.cmd.byId({
			id: _cmd.value,
			success: function (data) {
                console.log("cmd_cible: " + data.name)
            }
        })*/
    }
}
