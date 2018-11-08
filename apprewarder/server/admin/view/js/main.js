function renderChart(o) {
    var chartName = o.chartName, chartElement= '#' + o.chartElement,colors= o.chartColors,startDate = o.startDate,endDate= o.endDate,prefix = ((typeof o.prefix == 'string')? o.prefix:''),suffix = ((typeof o.suffix == 'string')? o.suffix:''),format= ((typeof o.format == 'string')? o.format:','),chartType=(typeof o.chartType == 'string')? o.chartType:'lineChart';
    url = '/chart/' + chartName;
    $.ajax({url:url,success:function(data){
        var chart,chartData = JSON.parse(data);
        NProgress.done();
        switch(chartType)
        {

            case 'lineChart':
            default:
                var chart = nv.models.lineChart()
                    .useInteractiveGuideline(true)
                    .x(function(d) { return d[0] })
                    .y(function(d) { return d[1] })
                    .color(colors)
                    .transitionDuration(300);
                break;

        }


        nv.addGraph(function() {



            chart.xAxis
                .tickFormat(function(d) { return d3.time.format('%m/%d')(new Date(d)) });

            chart.yAxis
                .tickFormat(function(d) { return  prefix + d3.format(format)(d) + suffix});

            d3.select(chartElement)
                .datum(chartData)
                .transition().duration(1000)
                .call(chart)
                // .transition().duration(0)
                .each('start', function() {
                    setTimeout(function() {
                        //d3.selectAll("foo").text(function(d) { return "$" + format(d); });
                        d3.selectAll(chartElement + ' *').each(function() {
                            console.log('start',this.__transition__, this)
                            // while(this.__transition__)
                            if(this.__transition__)
                                this.__transition__.duration = 1;

                        })
                    }, 0)
                });



            nv.utils.windowResize(chart.update);
            return chart;
        });

    },
        error:function() {
            console.log('error loading the content');
            return;
        },
        cache:false
    });
}




function modal(o)
{
    var btn = '',btnClose='',charClass;

    switch(o.modalType)
    {
        default:case 'undefined':case '0': charClass='hide'; break;
        case 1:charClass = 'modal-normal';break; //normal
        case 2:charClass = 'modal-success glyphicon glyphicon-ok';break; //success
        case 3:charClass = 'modal-error glyphicon glyphicon-exclamation-sign';break; //error
    }

    for(var k in o.modalButtons)
    {
        if(typeof o.modalButtons[k].class == 'string') {
            var btnClass = o.modalButtons[k].class;
        } else { var btnClass = '';}
        if(k == 0) { btnClose ='<button id="modal-btn-'+k+'" class="' + btnClass + ' modal-btn btn btn-default btn-lg">' + o.modalButtons[k].text +'</button>';}
        else {
            btn+='<button id="modal-btn-' + k +'" class="' +btnClass +' modal-btn btn btn-default btn-lg">' + o.modalButtons[k].text + '</button>';
        }
    }

    var modalSrc = '<div class="modal-container"><div class="modal-header"><h2 class="' + charClass + '">'+ o.modalTitle+'</h2></div><div class="modal-message">'+ o.modalMessage+'</div><div class="modal-btn-container">'+btn+btnClose+'</div></div>';
    //console.log('ITEMS: \n COUNT: ' + $.magnificPopup.instance.items);//.length + $.magnificPopup.instance.items[1]);
    //$.magnificPopup.instance.items[1];

    var itemData = {
        src: modalSrc,
        type: 'inline'
    };

    var modalData = {
        items:itemData,
        removalDelay: 300,
        mainClass: 'mfp-fade',

        callbacks: {

            beforeOpen: function() {
                //$.magnificPopup.close();
                console.log('before OPEN triggered!');
            },
            beforeClose:function(){
                $('.modal-btn').each(function(i,v){
                    $('#' + $(this).attr('id')).unbind();
                    console.log('click event removed for ' + ' #' + $(this).attr('id'));

                });
            },
            beforeOpen: function() {
                //$.magnificPopup.close();
                console.log('before OPEN triggered!');
            },
            open:function()
            {
                for(var k in o.modalButtons)
                {
                    console.log('adding click event to: modal-btn-' + k);
                    var btnInstance = $('#modal-btn-' + k);
                    if(k == 0) {
                        console.log('modal-btn-' + k + ' assigned as close button');
                        $('.mfp-close').addClass('hide');

                        if(typeof o.modalButtons[k].callback == 'function') {var f = o.modalButtons[k].callback;btnInstance.data('f',f);}
                        btnInstance.click(function(){
                            if(typeof  $(this).data('f') == 'function') {$(this).data('f')();}
                            $.magnificPopup.instance.close();
                        });
                    } else {
                        if(typeof o.modalButtons[k].callback == 'function'){var f = o.modalButtons[k].callback;btnInstance.data('f',f);}
                        btnInstance.click(function(){
                            if(typeof  $(this).data('f') == 'function') {$(this).data('f')();}
                        });
                    }
                }
            },
            change:function()
            {

            }
        }
    };


    if(!$.magnificPopup.instance.isOpen)
    {
        console.log('ITEM NOT VISIBLE OR NOT UNDEFINED');
        console.log('isOpen:' + $.magnificPopup.instance.isOpen);
        $.magnificPopup.instance.open(modalData);

    } else if($.magnificPopup.instance.isOpen ) {
        console.log('ITEMS DOES EXIST!:' + typeof $.magnificPopup.instance.items);
        console.log('isOpen:' + $.magnificPopup.instance.isOpen);
        $('.modal-btn').each(function(i,v){
            $('#' + $(this).attr('id')).unbind();
            console.log('click event removed for ' + ' #' + $(this).attr('id'));
        });
        $.magnificPopup.instance.items[0] = itemData;

        $.magnificPopup.instance.updateItemHTML();
        console.log('CONTENT CHANGED. O:' + o.modalButtons);

        for(var k in o.modalButtons)
        {
            console.log('adding click event to: modal-btn-' + k);
            var btnInstance = $('#modal-btn-' + k);
            if(k == 0) {
                console.log('modal-btn-' + k + ' assigned as close button');
                $('.mfp-close').addClass('hide');

                if(typeof o.modalButtons[k].callback == 'function') {var f = o.modalButtons[k].callback;btnInstance.data('f',f);}
                btnInstance.click(function(){
                    if(typeof  $(this).data('f') == 'function') {$(this).data('f')();}
                    $.magnificPopup.instance.close();
                });
            } else {
                if(typeof o.modalButtons[k].callback == 'function'){var f = o.modalButtons[k].callback;btnInstance.data('f',f);}

                btnInstance.click(function(){
                    if(typeof  $(this).data('f') == 'function') {$(this).data('f')();}
                });
            }
        }

    }
    else {
        console.log('some other strange case happened');
        console.log('isOpen:' + $.magnificPopup.instance.isOpen +' itemsTypeof:'+ typeof $.magnificPopup.instance.items)
    }
}


$(document).ready(function() {

/*
     modal({
     modalType:2, //error
     modalTitle:'Yar, oops!',
     modalMessage:'My apologies. It looks like here was an error requesting that page. If problems continue, please contact us at:',
     modalButtons:{
     0:{text:'OK',callback:function(){
     scroll(0,0);

     }}
     }
     });
*/
    NProgress.start();








});