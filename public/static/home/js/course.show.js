layui.use(['jquery', 'rate', 'helper'], function () {

    var $ = layui.jquery;
    var rate = layui.rate;
    var helper = layui.helper;

    rate.render({
        elem: '#star5',
        value: 5,
        readonly: true
    });

    rate.render({
        elem: '#star4',
        value: 4,
        readonly: true
    });

    rate.render({
        elem: '#star3',
        value: 3,
        readonly: true
    });

    rate.render({
        elem: '#star2',
        value: 2,
        readonly: true
    });

    rate.render({
        elem: '#star1',
        value: 1,
        readonly: true
    });

    /**
     * 收藏课程
     */
    $('.icon-star').on('click', function () {
        var $this = $(this);
        var $parent = $this.parent();
        var $favoriteCount = $parent.next();
        var favoriteCount = $favoriteCount.data('count');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $parent.data('url'),
                success: function () {
                    if ($this.hasClass('layui-icon-star-fill')) {
                        $this.removeClass('layui-icon-star-fill');
                        $this.addClass('layui-icon-star');
                        favoriteCount--;
                    } else {
                        $this.removeClass('layui-icon-star');
                        $this.addClass('layui-icon-star-fill');
                        favoriteCount++;
                    }
                    $favoriteCount.data('count', favoriteCount).text(favoriteCount);
                }
            });
        });
    });

    /**
     * 开始学习
     */
    $('.btn-study').on('click', function () {
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    /**
     * 浏览章节
     */
    $('.lesson-item').on('click', function () {
        if ($(this).hasClass('deny')) {
            return false;
        }
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    /**
     * 购买（课程或套餐)
     */
    $('body').on('click', '.btn-buy', function () {
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    /**
     * 点赞（评价）
     */
    $('body').on('click', '.review-like', function () {
        var $this = $(this);
        var $likeCount = $this.next();
        var likeCount = $likeCount.data('count');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $this.data('url'),
                success: function () {
                    if ($this.hasClass('liked')) {
                        $this.removeClass('liked');
                        likeCount--;
                    } else {
                        $this.addClass('liked');
                        likeCount++;
                    }
                    $likeCount.data('count', likeCount).text(likeCount);
                }
            });
        });
    });

    if ($('#tab-reviews').length > 0) {
        var $tabReviews = $('#tab-reviews');
        helper.ajaxLoadHtml($tabReviews.data('url'), $tabReviews.attr('id'));
    }

    if ($('#tab-resources').length > 0) {
        var $tabResources = $('#tab-resources');
        helper.ajaxLoadHtml($tabResources.data('url'), $tabResources.attr('id'));
    }

    if ($('#tab-packages').length > 0) {
        var $tabPackages = $('#tab-packages');
        helper.ajaxLoadHtml($tabPackages.data('url'), $tabPackages.attr('id'));
    }

    if ($('#sidebar-related').length > 0) {
        var $sdRelated = $('#sidebar-related');
        helper.ajaxLoadHtml($sdRelated.data('url'), $sdRelated.attr('id'));
    }

});
