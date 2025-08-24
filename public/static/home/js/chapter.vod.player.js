layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 15000;
    var userId = window.user.id;
    var requestId = helper.getRequestId();
    var chapterId = $('input[name="chapter.id"]').val();
    var cover = $('input[name="chapter.cover"]').val();
    var position = $('input[name="chapter.me.position"]').val();
    var learningUrl = $('input[name="chapter.learning_url"]').val();
    var playUrls = JSON.parse($('input[name="chapter.play_urls"]').val());

    var rates = [
        {name: 'hd', label: 'HD'},
        {name: 'sd', label: 'SD'},
        {name: 'fd', label: 'FD'},
    ];

    var quality = [];

    $.each(rates, function (k, rate) {
        if (playUrls.hasOwnProperty(rate.name)) {
            quality.push({
                name: rate.label,
                url: playUrls[rate.name]['url'],
            });
        }
    });

    var options = {
        container: document.getElementById('player'),
        lang: 'en',
        video: {
            pic: cover,
            quality: quality,
            defaultQuality: 0,
        }
    }

    var player = new DPlayer(options);

    player.on('play', function () {
        play();
    });

    player.on('pause', function () {
        pause();
    });

    player.on('ended', function () {
        ended();
    });

    /**
     * 屏蔽播放器右键
     */
    $('#player').on('contextmenu', function(e) {
        $('.dplayer-menu, .dplayer-mask').hide();
        e.preventDefault();
    });

    var $playMask = $('#play-mask');

    /**
     * 播放器中央播放按钮点击事件
     */
    $playMask.on('click', function () {
        $(this).hide();
        player.toggle();
    });

    var lastPosition = getLastPosition();

    /**
     * 上次播放位置
     */
    if (lastPosition > 0) {
        player.seek(lastPosition);
    }

    function getPositionKey() {
        return 'chapter:' + chapterId + ':position';
    }

    function getLastPosition() {
        var key = getPositionKey();
        var value = localStorage.getItem(key);
        return value != null ? parseInt(value) : position;
    }

    function setLastPosition(value) {
        var key = getPositionKey();
        localStorage.setItem(key, value);
    }

    function clearLearningInterval() {
        if (interval != null) {
            clearInterval(interval);
            interval = null;
        }
    }

    function setLearningInterval() {
        interval = setInterval(learning, intervalTime);
    }

    function play() {
        hidePlayMask();
        clearLearningInterval();
        setLearningInterval();
    }

    function pause() {
        showPlayMask();
        clearLearningInterval();
    }

    function ended() {
        showPlayMask();
        learning();
        setLastPosition(0);
        clearLearningInterval();
    }

    function showPlayMask(){
        $playMask.show();
    }

    function hidePlayMask(){
        $playMask.hide();
    }

    function learning() {
        setLastPosition(player.video.currentTime);
        if (userId !== '0') {
            $.ajax({
                type: 'POST',
                url: learningUrl,
                data: {
                    request_id: requestId,
                    interval_time: intervalTime,
                    position: player.video.currentTime,
                }
            });
        }
    }
});
