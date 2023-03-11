(function ($) {
    "use strict";

    //Normal Video
    $('.myVideo').on('ended', function (){
        callCompleteCourse();
    })

    // Vimeo video
    $(document).ready(function(){

        var vimeoVideoSource = $('.vimeoVideoSource').val();
        if (vimeoVideoSource) {
            var iframe = $('#playerVideoVimeo iframe');
            var player = new Vimeo.Player(iframe);

            player.on('ended', function() {
                callCompleteCourse();
            });
        }

    });

})(jQuery)

function callCompleteCourse(){
    $.ajax({
        type: "GET",
        url: videoCompletedRoute,
        data: {'course_id': course_id, 'lecture_id': lecture_id,  'enrollment_id': enrollment_id},
        datatype: "json",
        success: function (response) {
            toastr.options.positionClass = 'toast-bottom-right';
            if (nextLectureRoute) {
                window.location.href = nextLectureRoute;
            } else {
                location.reload();
            }
        },
        error: function (error) {

        },
    });
}
