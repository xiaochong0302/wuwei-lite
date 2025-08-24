<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

$lang = [];

$lang['sys.unauthorized'] = 'Authentication failed';
$lang['sys.forbidden'] = 'Access denied';
$lang['sys.bad_request'] = 'Bad request';
$lang['sys.not_found'] = 'Resource not found';
$lang['sys.server_error'] = 'Internal server error';
$lang['sys.service_unavailable'] = 'Service unavailable';
$lang['sys.trans_rollback'] = 'Transaction rolled back';
$lang['sys.unknown_error'] = 'Unknown error';

$lang['security.invalid_csrf_token'] = 'Invalid CSRF token';
$lang['security.invalid_http_referer'] = 'Invalid request referer';

$lang['verify.invalid_email'] = 'Invalid email';
$lang['verify.invalid_code'] = 'Invalid verification code';
$lang['verify.invalid_email_code'] = 'Invalid email verification code';
$lang['verify.send_email_failed'] = 'Failed to send email';

$lang['role.not_found'] = 'Role not found';
$lang['role.name_too_short'] = 'Name too short (less than 2 characters)';
$lang['role.name_too_long'] = 'Name too long (more than 30 characters)';
$lang['role.summary_too_long'] = 'Description too long (more than 255 characters)';
$lang['role.routes_required'] = 'Role permissions required';

$lang['account.not_found'] = 'Account not found';
$lang['account.locked'] = 'Account locked, login denied';
$lang['account.register_disabled'] = 'Registration is disabled';
$lang['account.invalid_email'] = 'Invalid email';
$lang['account.invalid_pwd'] = 'Invalid password (letters|numbers|special characters 6-16 characters)';
$lang['account.email_taken'] = 'Email already taken';
$lang['account.pwd_not_match'] = 'Passwords do not match';
$lang['account.origin_pwd_incorrect'] = 'Original password incorrect';
$lang['account.login_pwd_incorrect'] = 'Login password incorrect';

$lang['user.not_found'] = 'User not found';
$lang['user.name_taken'] = 'Name already taken';
$lang['user.title_too_long'] = 'Title too long (more than 30 characters)';
$lang['user.about_too_long'] = 'Bio too long (more than 255 characters)';
$lang['user.invalid_avatar'] = 'Invalid avatar';
$lang['user.invalid_edu_role'] = 'Invalid teaching role';
$lang['user.invalid_admin_role'] = 'Invalid admin role';
$lang['user.invalid_lock_status'] = 'Invalid lock status';
$lang['user.invalid_lock_expiry_time'] = 'Invalid lock expiration';
$lang['user.invalid_vip_status'] = 'Invalid VIP status';
$lang['user.invalid_vip_expiry_time'] = 'Invalid VIP expiration';

$lang['category.not_found'] = 'Category not found';
$lang['category.parent_not_found'] = 'Parent category not found';
$lang['category.invalid_priority'] = 'Invalid priority value (range: 1-255)';
$lang['category.invalid_publish_status'] = 'Invalid publish status';
$lang['category.name_too_short'] = 'Name too short (less than 2 characters)';
$lang['category.name_too_long'] = 'Name too long (more than 30 characters)';
$lang['category.has_child_node'] = 'Operation not allowed (has child nodes)';

$lang['nav.not_found'] = 'Navigation not found';
$lang['nav.parent_not_found'] = 'Parent navigation not found';
$lang['nav.invalid_url'] = 'Invalid URL';
$lang['nav.invalid_position'] = 'Invalid position';
$lang['nav.invalid_target'] = 'Invalid target';
$lang['nav.invalid_priority'] = 'Invalid priority value (range: 1-255)';
$lang['nav.invalid_publish_status'] = 'Invalid publish status';
$lang['nav.name_too_short'] = 'Name too short (less than 2 characters)';
$lang['nav.name_too_long'] = 'Name too long (more than 30 characters)';
$lang['nav.has_child_node'] = 'Operation not allowed (has child nodes)';

$lang['page.not_found'] = 'Page not found';
$lang['page.title_too_short'] = 'Title too short (less than 2 characters)';
$lang['page.title_too_long'] = 'Title too long (more than 120 characters)';
$lang['page.summary_too_long'] = 'Summary too long (more than 255 characters)';
$lang['page.keyword_too_long'] = 'Keyword too long (more than 120 characters)';
$lang['page.content_too_short'] = 'Content too short (less than 2 characters)';
$lang['page.content_too_long'] = 'Content too long (more than 30000 characters)';
$lang['page.invalid_publish_status'] = 'Invalid publish status';

$lang['slide.not_found'] = 'Slide not found';
$lang['slide.invalid_target'] = 'Invalid target';
$lang['slide.invalid_link'] = 'Invalid link';
$lang['slide.invalid_priority'] = 'Invalid priority value (range: 1-255)';
$lang['slide.invalid_cover'] = 'Invalid cover';
$lang['slide.title_too_short'] = 'Title too short (less than 2 characters)';
$lang['slide.title_too_long'] = 'Title too long (more than 120 characters)';
$lang['slide.summary_too_long'] = 'Summary too long (more than 255 characters)';
$lang['slide.invalid_publish_status'] = 'Invalid publish status';

$lang['course.not_found'] = 'Course not found';
$lang['course.title_too_short'] = 'Title too short (less than 2 characters)';
$lang['course.title_too_long'] = 'Title too long (more than 120 characters)';
$lang['course.keyword_too_long'] = 'Keyword too long (more than 120 characters)';
$lang['course.summary_too_long'] = 'Summary too long (more than 500 characters)';
$lang['course.details_too_long'] = 'Details too long (more than 30000 characters)';
$lang['course.invalid_level'] = 'Invalid level';
$lang['course.invalid_cover'] = 'Invalid cover';
$lang['course.invalid_regular_price'] = 'Invalid regular price (range: 0-999999)';
$lang['course.invalid_vip_price'] = 'Invalid VIP price (range: 0-999999)';
$lang['course.invalid_study_expiry'] = 'Invalid study expiry';
$lang['course.invalid_refund_expiry'] = 'Invalid refund expiry';
$lang['course.invalid_feature_status'] = 'Invalid feature status';
$lang['course.invalid_publish_status'] = 'Invalid publish status';
$lang['course.invalid_review_status'] = 'Invalid review status';
$lang['course.invalid_comment_status'] = 'Invalid comment status';
$lang['course.duplicate'] = 'This course may already exist';

$lang['course_user.not_found'] = 'Course user not found';
$lang['course_user.invalid_expiry_time'] = 'Invalid expiration time';
$lang['course_user.review_not_allowed'] = 'Reviews are not allowed at this time';
$lang['course_user.review_not_enabled'] = 'Reviews are disabled for this course';
$lang['course_user.has_reviewed'] = 'Already reviewed this course';

$lang['chapter.not_found'] = 'Chapter not found';
$lang['chapter.parent_not_found'] = 'Parent chapter not found';
$lang['chapter.invalid_model'] = 'Invalid model';
$lang['chapter.invalid_priority'] = 'Invalid priority value (range: 1-255)';
$lang['chapter.invalid_publish_status'] = 'Invalid publish status';
$lang['chapter.title_too_short'] = 'Title too short (less than 2 characters)';
$lang['chapter.title_too_long'] = 'Title too long (more than 120 characters)';
$lang['chapter.summary_too_long'] = 'Summary too long (more than 255 characters)';
$lang['chapter.keyword_too_long'] = 'Keyword too long (more than 120 characters)';
$lang['chapter.child_existed'] = 'Operation not allowed (has child chapters)';
$lang['chapter.video_not_ready'] = 'Video not ready';
$lang['chapter.article_not_ready'] = 'Article not ready';

$lang['upload.not_found'] = 'Upload not found';

$lang['media.not_found']= 'Media not found';

$lang['chapter_article.not_found'] = 'Chapter read not found';
$lang['chapter_article.content_too_short'] = 'Content too short (less than 2 characters)';
$lang['chapter_article.content_too_long'] = 'Content too long (more than 30000 characters)';

$lang['package.not_found'] = 'Package not found';
$lang['package.title_too_short'] = 'Title too short (less than 2 characters)';
$lang['package.title_too_long'] = 'Title too long (more than 120 characters)';
$lang['package.summary_too_long'] = 'Summary too long (more than 255 characters)';
$lang['package.invalid_cover'] = 'Invalid cover';
$lang['package.invalid_regular_price'] = 'Invalid regular price (range: 1-999999)';
$lang['package.invalid_vip_price'] = 'Invalid VIP price (range: 1-999999)';
$lang['package.invalid_publish_status'] = 'Invalid publish status';

$lang['vip.not_found'] = 'VIP not found';
$lang['vip.invalid_price'] = 'Invalid price (range: 1-999999)';
$lang['vip.invalid_expiry'] = 'Invalid period (range: 1-60)';

$lang['order.not_found'] = 'Order not fund';
$lang['order.invalid_item_type'] = 'Invalid product type';
$lang['order.invalid_amount'] = 'Invalid amount';
$lang['order.invalid_status'] = 'Invalid status';
$lang['order.is_delivering'] = 'The order has entered the delivery phase';
$lang['order.has_bought_course'] = 'This course has already been purchased';
$lang['order.has_bought_package'] = 'This package has already been purchased';
$lang['order.pay_not_allowed'] = 'Payment is currently not allowed for this order';
$lang['order.cancel_not_allowed'] = 'Cancellation is currently not allowed for this order';
$lang['order.refund_not_allowed'] = 'Refund requests are currently not allowed';
$lang['order.refund_not_supported'] = 'Refund not supported for this item';
$lang['order.refund_request_existed'] = 'Duplicate refund request detected';

$lang['refund.not_found'] = 'Refund not found';
$lang['refund.apply_note_too_short'] = 'Refund reason is too short (less than 2 characters)';
$lang['refund.apply_note_too_long'] = 'Refund reason is too long (more than 255 characters)';
$lang['refund.review_note_too_short'] = 'Review note is too short (less than 2 characters)';
$lang['refund.review_note_too_long'] = 'Review note is too long (more than 255 characters)';
$lang['refund.cancel_not_allowed'] = 'Refund cancellation is not allowed currently';
$lang['refund.review_not_allowed'] = 'Refund review is not allowed currently';
$lang['refund.invalid_amount'] = 'Invalid refund amount';
$lang['refund.invalid_status'] = 'Invalid refund status';

$lang['review.not_found'] = 'Review not found';
$lang['review.invalid_rating'] = 'Invalid rating (range: 1-5)';
$lang['review.invalid_publish_status'] = 'Invalid publish status';
$lang['review.content_too_short'] = 'Content too short (less than 10 characters)';
$lang['review.content_too_long'] = 'Content too long (more than 255 characters)';
$lang['review.edit_not_allowed'] = 'Editing not allowed currently';

$lang['comment.not_found'] = 'Comment not found';
$lang['comment.parent_not_found'] = 'Parent comment not found';
$lang['comment.invalid_publish_status'] = 'Invalid publish status';
$lang['comment.content_too_short'] = 'Content too short (less than 2 characters)';
$lang['comment.content_too_long'] = 'Content too long (more than 255 characters)';

$lang['user_limit.reach_favorite_limit'] = 'Exceeded favorite limit';
$lang['user_limit.reach_daily_comment_limit'] = 'Exceeded daily comment limit';
$lang['user_limit.reach_daily_order_limit'] = 'Exceeded daily order limit';
$lang['user_limit.reach_daily_like_limit'] = 'Exceeded daily like limit';

$lang['course_query.invalid_category'] = 'Invalid category';
$lang['course_query.invalid_level'] = 'Invalid level';
$lang['course_query.invalid_sort'] = 'Invalid sort';

$lang['learning.invalid_request_id'] = 'Invalid request ID';
$lang['learning.invalid_interval_time'] = 'Invalid interval time';
$lang['learning.invalid_position'] = 'Invalid playback position';

return $lang;
