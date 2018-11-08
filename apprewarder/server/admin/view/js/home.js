$(document).ready(function() {


    $('#report-user-stats div.panel').height($('#report-global-stats div.panel').height());
    $('#report-user-activity').height($('#report-network-revenue div.panel').height());

    //default colors
    var colors = d3.scale.category20(),keyColor = function(d, i) {return colors(d.key)};


    platformColors = ["#198acb","#6db914"];
    networkColors = ["#008aff","#4f5254","#c81123","#11c7a3","#d4d611"];
    marginColors = ["#62c711","#244608","#c51022"];
    monetizationColors = ["#c81224","#f06f7c","#c0f297","#5fc30f","#ffab81"];
    growthColors = ["#096bb7","#7cbae9"];

    renderChart({
        chartName:'system_monetization_total',
        chartElement:'chart-home-monetization',
        chartColors:monetizationColors,
        startDate:'',
        endDate:'',
        chartType:'',
        prefix:'$',
        format:',.2f'
    });


    renderChart({
        chartName:'system_user_activity_all',
        chartElement:'chart-user-activity',
        chartColors:keyColor,
        startDate:'',
        endDate:'',
        chartType:'',
        prefix:'',
        format:''
    });

    $('#system_user_activity_dau_platform').click(function(e){
        e.preventDefault();
        $('#system-growth').children('li').removeClass('active');
        $(this).parent().addClass('active');
        renderChart({
            chartName:'system_user_activity_dau_platform',
            chartElement:'chart-user-activity',
            chartColors:platformColors,
            startDate:'',
            endDate:'',
            chartType:'',
            prefix:'',
            format:''
        });
    });

    $('#system_user_activity_new_users_platform').click(function(e){
        e.preventDefault();
        $('#system-growth').children('li').removeClass('active');
        $(this).parent().addClass('active');
        renderChart({
            chartName:'system_user_activity_new_users_platform',
            chartElement:'chart-user-activity',
            chartColors:platformColors,
            startDate:'',
            endDate:'',
            chartType:'',
            prefix:'',
            format:''
        });
    });

    $('#system_user_activity_all').click(function(e){
        e.preventDefault();
        $('#system-growth').children('li').removeClass('active');
        $(this).parent().addClass('active');
        renderChart({
            chartName:'system_user_activity_all',
            chartElement:'chart-user-activity',
            chartColors:growthColors,
            startDate:'',
            endDate:'',
            chartType:'',
            prefix:'',
            format:''
        });
    });

    $('#system_monetization_total').click(function(e){
        e.preventDefault();
        $('#system-monetization').children('li').removeClass('active');
        $(this).parent().addClass('active');
        renderChart({
            chartName:'system_monetization_total',
            chartElement:'chart-home-monetization',
            chartColors:monetizationColors,
            startDate:'',
            endDate:'',
            chartType:'',
            prefix:'$',
            format:',.2f'
        });
    });

    $('#system_monetization_network').click(function(e){
        e.preventDefault();

        $('#system-monetization').children('li').removeClass('active');
        $(this).parent().addClass('active');
        renderChart({
            chartName:'system_monetization_network',
            chartElement:'chart-home-monetization',
            chartColors:networkColors,
            startDate:'',
            endDate:'',
            chartType:'',
            prefix:'$',
            format:',.2f'
        });
    });

    $('#system_monetization_margin').click(function(e){
        e.preventDefault();
        $('#system-monetization').children('li').removeClass('active');
        $(this).parent().addClass('active');
        colors = d3.scale.category10();
        keyColor = function(d, i) {return colors(d.key)};
        renderChart({
            chartName:'system_monetization_margin',
            chartElement:'chart-home-monetization',
            chartColors:marginColors,
            startDate:'',
            endDate:'',
            chartType:'',
            suffix:'%',
            format:'.2f'
        });
    });

});
