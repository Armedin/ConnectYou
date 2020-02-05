<?php
error_reporting(E_ALL);
include __DIR__.'/functions.php';
date_default_timezone_set(timezone());

/**
* You can remove an emoticon below.
*/
function smile_emoji()
{
    $emoji = array('1f385', '1f3c2', '1f3c3', '1f3c4', '1f3c7', '1f3ca', '1f3cb', '1f3cc', '1f440', '1f441-200d-1f5e8', '1f441', '1f442', '1f443', '1f444', '1f445', '1f446', '1f447', '1f448', '1f449', '1f44a', '1f44b', '1f44c', '1f44d', '1f44e', '1f44f', '1f450', '1f451', '1f452', '1f453', '1f454', '1f455', '1f456', '1f457', '1f458', '1f459', '1f460', '1f461', '1f462', '1f463', '1f464', '1f465', '1f466', '1f467', '1f468-200d-1f468-200d-1f466-200d-1f466', '1f468-200d-1f468-200d-1f466', '1f468-200d-1f468-200d-1f467-200d-1f466', '1f468-200d-1f468-200d-1f467-200d-1f467', '1f468-200d-1f468-200d-1f467', '1f468-200d-1f469-200d-1f466-200d-1f466', '1f468-200d-1f469-200d-1f466', '1f468-200d-1f469-200d-1f467-200d-1f466', '1f468-200d-1f469-200d-1f467-200d-1f467', '1f468-200d-1f469-200d-1f467', '1f468-200d-2764-fe0f-200d-1f468', '1f468-200d-2764-fe0f-200d-1f48b-200d-1f468', '1f468', '1f469-200d-1f469-200d-1f466-200d-1f466', '1f469-200d-1f469-200d-1f466', '1f469-200d-1f469-200d-1f467-200d-1f466', '1f469-200d-1f469-200d-1f467-200d-1f467', '1f469-200d-1f469-200d-1f467', '1f469-200d-2764-fe0f-200d-1f468', '1f469-200d-2764-fe0f-200d-1f469', '1f469-200d-2764-fe0f-200d-1f48b-200d-1f468', '1f469-200d-2764-fe0f-200d-1f48b-200d-1f469', '1f469', '1f46a', '1f46b', '1f46c', '1f46d', '1f46e', '1f46f', '1f470', '1f471', '1f473', '1f474', '1f475', '1f476', '1f477', '1f478', '1f479', '1f47a', '1f47b', '1f47c', '1f47d', '1f47f', '1f480', '1f481', '1f482', '1f483', '1f484', '1f485', '1f486', '1f487', '1f48b', '1f48d', '1f48e', '1f48f', '1f491', '1f4a9', '1f4aa', '1f574', '1f575', '1f590', '1f595', '1f596', '1f5e3', '1f600', '1f601', '1f602', '1f603', '1f604', '1f605', '1f606', '1f607', '1f608', '1f609', '1f60a', '1f60b', '1f60c', '1f60d', '1f60e', '1f60f', '1f610', '1f611', '1f612', '1f613', '1f614', '1f615', '1f616', '1f617', '1f618', '1f619', '1f61a', '1f61b', '1f61c', '1f61d', '1f61e', '1f61f', '1f620', '1f621', '1f622', '1f623', '1f624', '1f625', '1f626', '1f627', '1f628', '1f629', '1f62a', '1f62b', '1f62c', '1f62d', '1f62e', '1f62f', '1f630', '1f631', '1f632', '1f633', '1f634', '1f635', '1f636', '1f637', '1f638', '1f639', '1f63a', '1f63b', '1f63c', '1f63d', '1f63e', '1f63f', '1f640', '1f641', '1f642', '1f643', '1f644', '1f645', '1f646', '1f647', '1f648', '1f649', '1f64a', '1f64b', '1f64c', '1f64d', '1f64e', '1f64f', '1f6b4', '1f6b5', '1f6b6', '1f6c0', '1f6cc', '1f910', '1f911', '1f912', '1f913', '1f914', '1f915', '1f916', '1f917', '1f918', '261d', '2639', '263a', '26f7', '26f9', '270a', '270b', '270c', '270d');
    foreach ($emoji as $img) {
        $alt = '&#x'.str_replace('-', '&#x', $img);
        echo "<img ondragstart='return false;' draggable='false' class='emoji-link sprite sprite-" . $img . "' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='" . $alt . "' src='./include/web-imgs/blank.jpg' />";
    }
}

function nature_emoji()
{
    $emoji = array('1f302', '1f30a', '1f30d', '1f30e', '1f30f', '1f311', '1f312', '1f313', '1f314', '1f315', '1f316', '1f317', '1f318', '1f319', '1f31a', '1f31b', '1f31c', '1f31d', '1f31e', '1f31f', '1f320', '1f321', '1f324', '1f325', '1f326', '1f327', '1f328', '1f329', '1f32a', '1f32c', '1f331', '1f332', '1f333', '1f334', '1f335', '1f337', '1f338', '1f339', '1f33a', '1f33b', '1f33c', '1f33d', '1f33e', '1f33f', '1f340', '1f341', '1f342', '1f343', '1f383', '1f384', '1f38b', '1f400', '1f401', '1f402', '1f403', '1f404', '1f405', '1f406', '1f407', '1f408', '1f409', '1f40a', '1f40b', '1f40c', '1f40d', '1f40e', '1f40f', '1f410', '1f411', '1f412', '1f413', '1f414', '1f415', '1f416', '1f417', '1f418', '1f419', '1f41a', '1f41b', '1f41c', '1f41d', '1f41e', '1f41f', '1f420', '1f421', '1f422', '1f423', '1f424', '1f425', '1f426', '1f427', '1f428', '1f429', '1f42a', '1f42b', '1f42c', '1f42d', '1f42e', '1f42f', '1f430', '1f431', '1f432', '1f433', '1f434', '1f435', '1f436', '1f437', '1f438', '1f439', '1f43a', '1f43b', '1f43c', '1f43d', '1f43e', '1f43f', '1f490', '1f4a6', '1f4a7', '1f4a8', '1f4ab', '1f525', '1f54a', '1f577', '1f578', '1f980', '1f981', '1f982', '1f983', '1f984', '2600', '2601', '2602', '2603', '2604', '2614', '2618', '26c4', '26c5', '26c8');
    foreach ($emoji as $img) {
        echo "<img ondragstart='return false;' draggable='false' class='emoji-link sprite sprite-" . $img . "' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#x" . $img . "' src='./include/web-imgs/blank.jpg' />";
    }
}

function food_emoji()
{
    $emoji = array('1f32d', '1f32e', '1f32f', '1f330', '1f336', '1f344', '1f345', '1f346', '1f347', '1f348', '1f349', '1f34a', '1f34b', '1f34c', '1f34d', '1f34e', '1f34f', '1f350', '1f351', '1f352', '1f353', '1f354', '1f355', '1f356', '1f357', '1f358', '1f359', '1f35a', '1f35b', '1f35c', '1f35d', '1f35e', '1f35f', '1f360', '1f361', '1f362', '1f363', '1f364', '1f365', '1f366', '1f367', '1f368', '1f369', '1f36a', '1f36b', '1f36c', '1f36d', '1f36e', '1f36f', '1f370', '1f371', '1f372', '1f373', '1f374', '1f375', '1f376', '1f377', '1f378', '1f379', '1f37a', '1f37b', '1f37c', '1f37d', '1f37e', '1f37f', '1f382', '1f9c0', '2615');
    foreach ($emoji as $img) {
        echo "<img ondragstart='return false;' draggable='false' class='emoji-link sprite sprite-" . $img . "' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#x" . $img . "' src='./include/web-imgs/blank.jpg' />";
    }
}

function travel_emoji()
{
    $emoji = array('1f301', '1f303', '1f304', '1f305', '1f306', '1f307', '1f308', '1f309', '1f30b', '1f30c', '1f32b', '1f391', '1f3a0', '1f3a1', '1f3a2', '1f3aa', '1f3b4', '1f3cd', '1f3ce', '1f3d4', '1f3d5', '1f3d6', '1f3d7', '1f3d8', '1f3d9', '1f3da', '1f3db', '1f3dc', '1f3dd', '1f3de', '1f3df', '1f3e0', '1f3e1', '1f3e2', '1f3e3', '1f3e4', '1f3e5', '1f3e6', '1f3e8', '1f3e9', '1f3ea', '1f3eb', '1f3ec', '1f3ed', '1f3ef', '1f3f0', '1f492', '1f54b', '1f54c', '1f54d', '1f5fa', '1f5fb', '1f5fc', '1f5fd', '1f5fe', '1f680', '1f681', '1f682', '1f683', '1f684', '1f685', '1f686', '1f687', '1f688', '1f689', '1f68a', '1f68b', '1f68c', '1f68d', '1f68e', '1f68f', '1f690', '1f691', '1f692', '1f693', '1f694', '1f695', '1f696', '1f697', '1f698', '1f699', '1f69a', '1f69b', '1f69c', '1f69d', '1f69e', '1f69f', '1f6a0', '1f6a1', '1f6a2', '1f6a3', '1f6a4', '1f6a5', '1f6a6', '1f6a7', '1f6e3', '1f6e4', '1f6e5', '1f6e9', '1f6eb', '1f6ec', '1f6f0', '1f6f3', '26e9', '26ea', '26f0', '26f1', '26f2', '26f4', '26f5', '26fa', '2708');
    foreach ($emoji as $img) {
        echo "<img ondragstart='return false;' draggable='false' class='emoji-link sprite sprite-" . $img . "' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#x" . $img . "' src='./include/web-imgs/blank.jpg' />";
    }
}

function object_emoji()
{
    $emoji = array('1f380', '1f381', '1f388', '1f389', '1f38a', '1f38c', '1f38d', '1f38e', '1f38f', '1f390', '1f392', '1f393', '1f396', '1f397', '1f399', '1f39a', '1f39b', '1f39e', '1f39f', '1f3a3', '1f3a4', '1f3a5', '1f3a7', '1f3a8', '1f3a9', '1f3ab', '1f3ac', '1f3ae', '1f3af', '1f3b0', '1f3b1', '1f3b2', '1f3b3', '1f3b7', '1f3b8', '1f3b9', '1f3ba', '1f3bb', '1f3bc', '1f3bd', '1f3be', '1f3bf', '1f3c0', '1f3c1', '1f3c5', '1f3c6', '1f3c8', '1f3c9', '1f3cf', '1f3d0', '1f3d1', '1f3d2', '1f3d3', '1f3ee', '1f3f7', '1f3f8', '1f3f9', '1f3fa', '1f45a', '1f45b', '1f45c', '1f45d', '1f45e', '1f45f', '1f47e', '1f488', '1f489', '1f48a', '1f48c', '1f4a1', '1f4a3', '1f4b3', '1f4b4', '1f4b5', '1f4b6', '1f4b7', '1f4b8', '1f4ba', '1f4bb', '1f4bc', '1f4bd', '1f4be', '1f4bf', '1f4c0', '1f4c1', '1f4c2', '1f4c3', '1f4c4', '1f4c5', '1f4c6', '1f4c7', '1f4c8', '1f4c9', '1f4ca', '1f4cb', '1f4cc', '1f4cd', '1f4ce', '1f4cf', '1f4d0', '1f4d1', '1f4d2', '1f4d3', '1f4d4', '1f4d5', '1f4d6', '1f4d7', '1f4d8', '1f4d9', '1f4da', '1f4dc', '1f4dd', '1f4de', '1f4df', '1f4e0', '1f4e1', '1f4e2', '1f4e3', '1f4e4', '1f4e5', '1f4e6', '1f4e7', '1f4e8', '1f4e9', '1f4ea', '1f4eb', '1f4ec', '1f4ed', '1f4ee', '1f4ef', '1f4f0', '1f4f7', '1f4f8', '1f4f9', '1f4fa', '1f4fb', '1f4fc', '1f4fd', '1f4ff', '1f50b', '1f50c', '1f50d', '1f50e', '1f50f', '1f516', '1f517', '1f526', '1f527', '1f528', '1f529', '1f52a', '1f52b', '1f52c', '1f52d', '1f52e', '1f56f', '1f570', '1f573', '1f576', '1f579', '1f587', '1f58a', '1f58b', '1f58c', '1f58d', '1f5a5', '1f5a8', '1f5b1', '1f5b2', '1f5bc', '1f5c2', '1f5c3', '1f5c4', '1f5d1', '1f5d2', '1f5d3', '1f5dc', '1f5dd', '1f5de', '1f5e1', '1f5f3', '1f5ff', '1f6a8', '1f6a9', '1f6aa', '1f6ac', '1f6bd', '1f6bf', '1f6c1', '1f6cb', '1f6cd', '1f6ce', '1f6cf', '1f6e0', '1f6e1', '1f6e2', '231a', '231b', '2328', '23f0', '23f1', '23f2', '23f3', '260e', '2692', '2693', '2694', '2696', '2697', '2699', '26b0', '26b1', '26bd', '26be', '26cf', '26d1', '26d3', '26f3', '26f8', '26fd', '2702', '2709', '270f', '2712', 'e50a');
    foreach ($emoji as $img) {
        echo "<img ondragstart='return false;' draggable='false' class='emoji-link sprite sprite-" . $img . "' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#x" . $img . "' src='./include/web-imgs/blank.jpg' />";
    }
}

function symbol_emoji()
{
    $emoji = array('1f004', '1f0cf', '1f170', '1f171', '1f17e', '1f17f', '1f18e', '1f191', '1f192', '1f193', '1f194', '1f195', '1f196', '1f197', '1f198', '1f199', '1f19a', '1f1e6', '1f1e7', '1f1e8', '1f1e9', '1f1ea', '1f1eb', '1f1ec', '1f1ed', '1f1ee', '1f1ef', '1f1f0', '1f1f1', '1f1f2', '1f1f3', '1f1f4', '1f1f5', '1f1f6', '1f1f7', '1f1f8', '1f1f9', '1f1fa', '1f1fb', '1f1fc', '1f1fd', '1f1fe', '1f1ff', '1f201', '1f202', '1f21a', '1f22f', '1f232', '1f233', '1f234', '1f235', '1f236', '1f237', '1f238', '1f239', '1f23a', '1f250', '1f251', '1f300', '1f310', '1f386', '1f387', '1f3a6', '1f3ad', '1f3b5', '1f3b6', '1f3e7', '1f3f3', '1f3f4', '1f3f5', '1f493', '1f494', '1f495', '1f496', '1f497', '1f498', '1f499', '1f49a', '1f49b', '1f49c', '1f49d', '1f49e', '1f49f', '1f4a0', '1f4a2', '1f4a4', '1f4a5', '1f4ac', '1f4ad', '1f4ae', '1f4af', '1f4b0', '1f4b1', '1f4b2', '1f4b9', '1f4db', '1f4f1', '1f4f2', '1f4f3', '1f4f4', '1f4f5', '1f4f6', '1f500', '1f501', '1f502', '1f503', '1f504', '1f505', '1f506', '1f507', '1f508', '1f509', '1f50a', '1f510', '1f511', '1f512', '1f513', '1f514', '1f515', '1f518', '1f519', '1f51a', '1f51b', '1f51c', '1f51d', '1f51e', '1f51f', '1f520', '1f521', '1f522', '1f523', '1f524', '1f52f', '1f530', '1f531', '1f532', '1f533', '1f534', '1f535', '1f536', '1f537', '1f538', '1f539', '1f53a', '1f53b', '1f53c', '1f53d', '1f549', '1f54e', '1f550', '1f551', '1f552', '1f553', '1f554', '1f555', '1f556', '1f557', '1f558', '1f559', '1f55a', '1f55b', '1f55c', '1f55d', '1f55e', '1f55f', '1f560', '1f561', '1f562', '1f563', '1f564', '1f565', '1f566', '1f567', '1f5e8', '1f5ef', '1f6ab', '1f6ad', '1f6ae', '1f6af', '1f6b0', '1f6b1', '1f6b2', '1f6b3', '1f6b7', '1f6b8', '1f6b9', '1f6ba', '1f6bb', '1f6bc', '1f6be', '1f6c2', '1f6c3', '1f6c4', '1f6c5', '1f6d0', '203c', '2049', '2122', '2139', '2194', '2195', '2196', '2197', '2198', '2199', '21a9', '21aa', '23-20e3', '23cf', '23e9', '23ea', '23eb', '23ec', '23ed', '23ee', '23ef', '23f8', '23f9', '23fa', '24c2', '25aa', '25ab', '25b6', '25c0', '25fb', '25fc', '25fd', '25fe', '2611', '2620', '2622', '2623', '2626', '262a', '262e', '262f', '2638', '2648', '2649', '264a', '264b', '264c', '264d', '264e', '264f', '2650', '2651', '2652', '2653', '2660', '2663', '2665', '2666', '2668', '267b', '267f', '269b', '269c', '26a0', '26a1', '26aa', '26ab', '26ce', '26d4', '2705', '2714', '2716', '271d', '2721', '2728', '2733', '2734', '2744', '2747', '274c', '274e', '2753', '2754', '2755', '2757', '2763', '2764', '2795', '2796', '2797', '27a1', '27b0', '27bf', '2934', '2935', '2a-20e3', '2b05', '2b06', '2b07', '2b1b', '2b1c', '2b50', '2b55', '30-20e3', '3030', '303d', '31-20e3', '32-20e3', '3297', '3299', '33-20e3', '34-20e3', '35-20e3', '36-20e3', '37-20e3', '38-20e3', '39-20e3', 'a9', 'ae');
    foreach ($emoji as $img) {
        $alt = '&#x'.str_replace('-', '&#x', $img);
        echo "<img ondragstart='return false;' draggable='false' class='emoji-link sprite sprite-" . $img . "' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='" . $alt . "' src='./include/web-imgs/blank.jpg' />";
    }
}

function flag_emoji()
{
    $emoji = array('1f1e6-1f1e8', '1f1e6-1f1e9', '1f1e6-1f1ea', '1f1e6-1f1eb', '1f1e6-1f1ec', '1f1e6-1f1ee', '1f1e6-1f1f1', '1f1e6-1f1f2', '1f1e6-1f1f4', '1f1e6-1f1f6', '1f1e6-1f1f7', '1f1e6-1f1f8', '1f1e6-1f1f9', '1f1e6-1f1fa', '1f1e6-1f1fc', '1f1e6-1f1fd', '1f1e6-1f1ff', '1f1e7-1f1e6', '1f1e7-1f1e7', '1f1e7-1f1e9', '1f1e7-1f1ea', '1f1e7-1f1eb', '1f1e7-1f1ec', '1f1e7-1f1ed', '1f1e7-1f1ee', '1f1e7-1f1ef', '1f1e7-1f1f1', '1f1e7-1f1f2', '1f1e7-1f1f3', '1f1e7-1f1f4', '1f1e7-1f1f6', '1f1e7-1f1f7', '1f1e7-1f1f8', '1f1e7-1f1f9', '1f1e7-1f1fb', '1f1e7-1f1fc', '1f1e7-1f1fe', '1f1e7-1f1ff', '1f1e8-1f1e6', '1f1e8-1f1e8', '1f1e8-1f1e9', '1f1e8-1f1eb', '1f1e8-1f1ec', '1f1e8-1f1ed', '1f1e8-1f1ee', '1f1e8-1f1f0', '1f1e8-1f1f1', '1f1e8-1f1f2', '1f1e8-1f1f3', '1f1e8-1f1f4', '1f1e8-1f1f5', '1f1e8-1f1f7', '1f1e8-1f1fa', '1f1e8-1f1fb', '1f1e8-1f1fc', '1f1e8-1f1fd', '1f1e8-1f1fe', '1f1e8-1f1ff', '1f1e9-1f1ea', '1f1e9-1f1ec', '1f1e9-1f1ef', '1f1e9-1f1f0', '1f1e9-1f1f2', '1f1e9-1f1f4', '1f1e9-1f1ff', '1f1ea-1f1e6', '1f1ea-1f1e8', '1f1ea-1f1ea', '1f1ea-1f1ec', '1f1ea-1f1ed', '1f1ea-1f1f7', '1f1ea-1f1f8', '1f1ea-1f1f9', '1f1ea-1f1fa', '1f1eb-1f1ee', '1f1eb-1f1ef', '1f1eb-1f1f0', '1f1eb-1f1f2', '1f1eb-1f1f4', '1f1eb-1f1f7', '1f1ec-1f1e6', '1f1ec-1f1e7', '1f1ec-1f1e9', '1f1ec-1f1ea', '1f1ec-1f1eb', '1f1ec-1f1ec', '1f1ec-1f1ed', '1f1ec-1f1ee', '1f1ec-1f1f1', '1f1ec-1f1f2', '1f1ec-1f1f3', '1f1ec-1f1f5', '1f1ec-1f1f6', '1f1ec-1f1f7', '1f1ec-1f1f8', '1f1ec-1f1f9', '1f1ec-1f1fa', '1f1ec-1f1fc', '1f1ec-1f1fe', '1f1ed-1f1f0', '1f1ed-1f1f2', '1f1ed-1f1f3', '1f1ed-1f1f7', '1f1ed-1f1f9', '1f1ed-1f1fa', '1f1ee-1f1e8', '1f1ee-1f1e9', '1f1ee-1f1ea', '1f1ee-1f1f1', '1f1ee-1f1f2', '1f1ee-1f1f3', '1f1ee-1f1f4', '1f1ee-1f1f6', '1f1ee-1f1f7', '1f1ee-1f1f8', '1f1ee-1f1f9', '1f1ef-1f1ea', '1f1ef-1f1f2', '1f1ef-1f1f4', '1f1ef-1f1f5', '1f1f0-1f1ea', '1f1f0-1f1ec', '1f1f0-1f1ed', '1f1f0-1f1ee', '1f1f0-1f1f2', '1f1f0-1f1f3', '1f1f0-1f1f5', '1f1f0-1f1f7', '1f1f0-1f1fc', '1f1f0-1f1fe', '1f1f0-1f1ff', '1f1f1-1f1e6', '1f1f1-1f1e7', '1f1f1-1f1e8', '1f1f1-1f1ee', '1f1f1-1f1f0', '1f1f1-1f1f7', '1f1f1-1f1f8', '1f1f1-1f1f9', '1f1f1-1f1fa', '1f1f1-1f1fb', '1f1f1-1f1fe', '1f1f2-1f1e6', '1f1f2-1f1e8', '1f1f2-1f1e9', '1f1f2-1f1ea', '1f1f2-1f1eb', '1f1f2-1f1ec', '1f1f2-1f1ed', '1f1f2-1f1f0', '1f1f2-1f1f1', '1f1f2-1f1f2', '1f1f2-1f1f3', '1f1f2-1f1f4', '1f1f2-1f1f5', '1f1f2-1f1f6', '1f1f2-1f1f7', '1f1f2-1f1f8', '1f1f2-1f1f9', '1f1f2-1f1fa', '1f1f2-1f1fb', '1f1f2-1f1fc', '1f1f2-1f1fd', '1f1f2-1f1fe', '1f1f2-1f1ff', '1f1f3-1f1e6', '1f1f3-1f1e8', '1f1f3-1f1ea', '1f1f3-1f1eb', '1f1f3-1f1ec', '1f1f3-1f1ee', '1f1f3-1f1f1', '1f1f3-1f1f4', '1f1f3-1f1f5', '1f1f3-1f1f7', '1f1f3-1f1fa', '1f1f3-1f1ff', '1f1f4-1f1f2', '1f1f5-1f1e6', '1f1f5-1f1ea', '1f1f5-1f1eb', '1f1f5-1f1ec', '1f1f5-1f1ed', '1f1f5-1f1f0', '1f1f5-1f1f1', '1f1f5-1f1f2', '1f1f5-1f1f3', '1f1f5-1f1f7', '1f1f5-1f1f8', '1f1f5-1f1f9', '1f1f5-1f1fc', '1f1f5-1f1fe', '1f1f6-1f1e6', '1f1f7-1f1ea', '1f1f7-1f1f4', '1f1f7-1f1f8', '1f1f7-1f1fa', '1f1f7-1f1fc', '1f1f8-1f1e6', '1f1f8-1f1e7', '1f1f8-1f1e8', '1f1f8-1f1e9', '1f1f8-1f1ea', '1f1f8-1f1ec', '1f1f8-1f1ed', '1f1f8-1f1ee', '1f1f8-1f1ef', '1f1f8-1f1f0', '1f1f8-1f1f1', '1f1f8-1f1f2', '1f1f8-1f1f3', '1f1f8-1f1f4', '1f1f8-1f1f7', '1f1f8-1f1f8', '1f1f8-1f1f9', '1f1f8-1f1fb', '1f1f8-1f1fd', '1f1f8-1f1fe', '1f1f8-1f1ff', '1f1f9-1f1e6', '1f1f9-1f1e8', '1f1f9-1f1e9', '1f1f9-1f1eb', '1f1f9-1f1ec', '1f1f9-1f1ed', '1f1f9-1f1ef', '1f1f9-1f1f0', '1f1f9-1f1f1', '1f1f9-1f1f2', '1f1f9-1f1f3', '1f1f9-1f1f4', '1f1f9-1f1f7', '1f1f9-1f1f9', '1f1f9-1f1fb', '1f1f9-1f1fc', '1f1f9-1f1ff', '1f1fa-1f1e6', '1f1fa-1f1ec', '1f1fa-1f1f2', '1f1fa-1f1f8', '1f1fa-1f1fe', '1f1fa-1f1ff', '1f1fb-1f1e6', '1f1fb-1f1e8', '1f1fb-1f1ea', '1f1fb-1f1ec', '1f1fb-1f1ee', '1f1fb-1f1f3', '1f1fb-1f1fa', '1f1fc-1f1eb', '1f1fc-1f1f8', '1f1fd-1f1f0', '1f1fe-1f1ea', '1f1fe-1f1f9', '1f1ff-1f1e6', '1f1ff-1f1f2', '1f1ff-1f1fc');
    foreach ($emoji as $img) {
        $alt = '&#x'.str_replace('-', '&#x', $img);
        echo "<img ondragstart='return false;' draggable='false' class='emoji-link sprite sprite-" . $img . "' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='" . $alt . "' src='./include/web-imgs/blank.jpg' />";
    }
}
/**
* End of Emoticons
*/

/**
* Some chat stuff
*
* JQuery variables, User check, Load Previous Messages
*/
function chat($chat_get)
{
    $chat_id = chat_id($chat_get);    // Chat ID
    $user_id = user_id();    // User ID
    $user_name = user_name();    // Username

    /* Get the Chat Type */
    $type_query = db_query("SELECT `type` FROM `chat_room` WHERE `id_hash` = '$chat_get' LIMIT 1");
    $type_row = mysqli_fetch_assoc($type_query);
    $type = $type_row['type'];
    /*********************/

    /**
    * Some JS Variables
    *
    * Crypted Chat ID, Room Type
    */  
    echo "<script language='javascript' type='text/javascript'>";
        echo "var chat='" . $chat_get . "';";
        echo "var rmtyp='" . $type . "';";
    echo "</script>";

    if (userInRoom($user_id, $chat_id)) {
        // Check if the user is in the room
        loadMessages($chat_id, $user_id);
    } elseif (isKicked($chat_id, $user_id)) {
        // Check if the user is kicked.
        echo "<div class='chat-msgs'><div class='system'>You have kicked.</div></div>";
    } else {
        echo "<p>You do not have permission to see this page.</p>";
    }
}

/**
* Loads previous messages if enabled in the config
*/
function loadMessages($chat_id, $user_id)
{
    if (save_messages() == 1) {
        if (userInRoom($user_id, $chat_id)) {
            /*
            * Check if user is in the room
            */
            $user_stat_q = db_query("SELECT `status`, `last_load_time` FROM `chat_members` WHERE `user_id` = '$user_id' && `chat_room` = '$chat_id' LIMIT 1");
            $user_stat_r = mysqli_fetch_assoc($user_stat_q);
            $user_stat = $user_stat_r['status'];
            $last_load_time = $user_stat_r['last_load_time'];

            if ($user_stat == 2 || $user_stat == 3 || $user_stat == 6) {
                /*
                * Chat has cleared before or user deleted a personal message conversation
                */
                $messages = db_query("SELECT `message`, `from_id`, `from_name`, `ID`, `time`, `type`, `mime`, `file_name` FROM `chat_messages` WHERE `chat_room` = '$chat_id' && `time` >= '$last_load_time' ORDER BY `time` ASC");
            } else {
                $messages = db_query("SELECT `message`, `from_id`, `from_name`, `ID`, `time`, `type`, `mime`, `file_name` FROM `chat_messages` WHERE `chat_room` = '$chat_id' ORDER BY `time` ASC");
            }

            $unread_q = db_query("SELECT `msg_id` FROM `chat_unread` WHERE `chat_room` = '$chat_id' && `usr_id` = '$user_id' ORDER BY `msg_id` ASC LIMIT 1");
            $unread_r = mysqli_fetch_assoc($unread_q);
            $unread = $unread_r['msg_id'];
            $i = 0;
			$map_i = 0;
            while ($row = mysqli_fetch_array($messages)) {
                $rec_message = html_entity_decode($row['message']);

                $message_old = preg_replace("/\:\:e\|\|(.*?)\:\:/", "<img ondragstart='return false;' alt='&#x$1' src='./include/web-imgs/blank.jpg' style='background-image: url(\"./include/web-imgs/emojis.png\");' class='emoji-link-small sprite sprite-$1' draggable='false' />", $rec_message);
                $message = stripslashes(ban_word(preg_replace_callback('/(\ alt=\')(.*?)(\')/', function ($matches) {
                    return $matches[1].str_replace('-', '&#x', $matches[2]).$matches[3];
                }, $message_old)));
				
				if($row["type"] == "user_media_location") {
					$message = json_decode(json_encode(unserialize($message)), true);
				}

                if ($row['ID'] >= $unread && $i == 0 && !empty($unread)) {
                    /**
                    * Write Unread Messages
                    */
                    echo "<div id='unread' class='unread-msg'>Unread Messages</div>";
                    $i = 1;
                }
                echo "<div class='chat-msgs'>";

                if ($row['from_id'] != $user_id && chatType($chat_id) == 1 && $row['type'] == 'user') {
                    echo "<div class='other-msgs'><div class='other-usr-msg'>" . $message . "</div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 0  && $row['type'] == 'user') {
                    /**
					* Receive Group Message
                    */
					echo "<div class='other-msgs'><div class='username'>" . $row['from_name'] . "</div><div class='other-usr-msg'>" . $message . "</div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
                } elseif ($row['from_id'] == $user_id && $row['type'] == 'user') {
                    /**
					* Your Message
                    */
                    echo "<div class='my-msgs'><div class='my-usr-msg'>" . $message . "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 1 && $row['type'] == 'user_media_img') {
                    echo "<div class='other-msgs'><div class='other-usr-msg'><a href='" . $message . "' class='image-link'><div class='image-thumb'><img class='shared-images' src='" . $message . "' alt='' title=''/></div></a></div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 0  && $row['type'] == 'user_media_img') {
                    echo "<div class='other-msgs'><div class='username'>" . $row['from_name'] . "</div><div class='other-usr-msg'><a href='" . $message . "' class='image-link'><div class='image-thumb'><img class='shared-images' src='" . $message . "' alt='' title=''/></div></a></div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
                } elseif ($row['from_id'] == $user_id && $row['type'] == 'user_media_img') {
                    echo "<div class='my-msgs'><div class='my-usr-msg'><a href='" . $message . "' class='image-link'><div class='image-thumb'><img class='shared-images' src='" . $message . "' alt='' title=''/></div></a></div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 1 && $row['type'] == 'user_media_vid') {
                    echo "<div class='other-msgs'><div class='other-usr-msg'>";
                    if ($row['mime'] != 'video/mp4' && $row['mime'] != 'video/webm' && $row['mime'] != 'video/ogg') {
                        echo "<div class='file-bg'><a href='" . $message . "'><div class='file-bg-text'>Download</div></a></div>";
                    } else {
                        echo "<a href='" . $message . "' class='video-link'><div class='image-thumb'><video class='shared-vid' src='" . $message . "' type='" . $row['mime'] . "'><div class='file-bg'><div class='file-bg-text'>Download</div></div></video><div class='video-play'><i class='material-icons'>play_circle_outline</i></div></div></a>";
                    }
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 0  && $row['type'] == 'user_media_vid') {
                    echo "<div class='other-msgs'><div class='username'>" . $row['from_name'] . "</div><div class='other-usr-msg'>";
                    if ($row['mime'] != 'video/mp4' && $row['mime'] != 'video/webm' && $row['mime'] != 'video/ogg') {
                        echo "<div class='file-bg'><a href='" . $message . "'><div class='file-bg-text'>Download</div></a></div>";
                    } else {
                        echo "<a href='" . $message . "' class='video-link'><div class='image-thumb'><video class='shared-vid' src='" . $message . "' type='" . $row['mime'] . "'><div class='file-bg'><div class='file-bg-text'>Download</div></div></video><div class='video-play'><i class='material-icons'>play_circle_outline</i></div></div></a>";
                    }
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
                } elseif ($row['from_id'] == $user_id && $row['type'] == 'user_media_vid') {
                    echo "<div class='my-msgs'><div class='my-usr-msg'>";
                    if ($row['mime'] != 'video/mp4' && $row['mime'] != 'video/webm' && $row['mime'] != 'video/ogg') {
                        echo "<div class='file-bg'><a href='" . $message . "'><div class='file-bg-text'>Download</div></a></div>";
                    } else {
                        echo "<a href='" . $message . "' class='video-link'><div class='image-thumb'><video class='shared-vid' src='" . $message . "' type='" . $row['mime'] . "'><div class='file-bg'><div class='file-bg-text'>Download</div></div></video><div class='video-play'><i class='material-icons'>play_circle_outline</i></div></div></a>";
                    }
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 1 && $row['type'] == 'user_media_file') {
                    echo "<div class='other-msgs'><div class='other-usr-msg'><p><i name='" . $row['file_name'] . "' href='" . $message . "' class='material-icons clickable shared-file large download-file'>archive</i></p><p class='file-name'>" . $row['file_name'] . "</p></div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 0  && $row['type'] == 'user_media_file') {
                    echo "<div class='other-msgs'><div class='username'>" . $row['from_name'] . "</div><div class='other-usr-msg'><p><i name='" . $row['file_name'] . "' href='" . $message . "' class='material-icons clickable shared-file large download-file'>archive</i></p><p class='file-name'>" . $row['file_name'] . "</p></div><div class='msg-time left'>" . date('H:i', $row['time']).'</div></div>';
                } elseif ($row['from_id'] == $user_id && $row['type'] == 'user_media_file') {
                    echo "<div class='my-msgs'><div class='my-usr-msg'><p><i name='" . $row['file_name'] . "' href='" . $message . "' class='material-icons clickable shared-file large download-file'>archive</i></p><p class='file-name'>" . $row['file_name'] . "</p></div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 1 && $row['type'] == 'user_media_music') {
                    echo "<div class='other-msgs'><div class='other-usr-msg music-link'>";
                    if ($row['mime'] == 'audio/mpeg' || $row['mime'] == 'audio/wav' || $row['mime'] == 'audio/ogg' || $row['mime'] == 'audio/mp3') {
                        echo "<p><i href='" . $message . "' name='" . $row['file_name'] . "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" . $row['file_name'] . "</p><p class='shared-music'><audio src='" . $message . "' type='" . $row['mime'] . "' controls></audio></p>";
                    } else {
                        echo "<p><i href='" . $message . "' name='" . $row['file_name'] . "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" . $row['file_name'].'</p>';
                    }
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 0  && $row['type'] == 'user_media_music') {
                    echo "<div class='other-msgs'><div class='username'>" . $row['from_name'] . "</div><div class='other-usr-msg music-link'>";
                    if ($row['mime'] == 'audio/mpeg' || $row['mime'] == 'audio/wav' || $row['mime'] == 'audio/ogg' || $row['mime'] == 'audio/mp3') {
                        echo "<p><i href='" . $message . "' name='" . $row['file_name'] . "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" . $row['file_name'] . "</p><p class='shared-music'><audio src='" . $message . "' type='" . $row['mime'] . "' controls></audio></p>";
                    } else {
                        echo "<p><i href='" . $message . "' name='" . $row['file_name'] . "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" . $row['file_name'].'</p>';
                    }
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] == $user_id && $row['type'] == 'user_media_music') {
                    echo "<div class='my-msgs'><div class='my-usr-msg music-link'>";
                    if ($row['mime'] == 'audio/mpeg' || $row['mime'] == 'audio/wav' || $row['mime'] == 'audio/ogg' || $row['mime'] == 'audio/mp3') {
                        echo "<p><i href='" . $message . "' name='" . $row['file_name'] . "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" . $row['file_name'] . "</p><p class='shared-music'><audio src='" . $message . "' type='" . $row['mime'] . "' controls></audio></p>";
                    } else {
                        echo "<p><i href='" . $message . "' name='" . $row['file_name'] . "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" . $row['file_name'].'</p>';
                    }
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 1 && $row['type'] == 'user_media_voice_note') {
                    echo "<div class='other-msgs'><div class='other-usr-msg voice-note'>";
                    echo "<p class='shared-music'><audio src='" . $message . "' type='audio/wav' controls></audio></p>";
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 0  && $row['type'] == 'user_media_voice_note') {
                    echo "<div class='other-msgs'><div class='username'>" . $row['from_name'] . "</div><div class='other-usr-msg voice-note'>";
                    echo "<p class='shared-music'><audio src='" . $message . "' type='audio/wav' controls></audio></p>";
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] == $user_id && $row['type'] == 'user_media_voice_note') {
                    echo "<div class='my-msgs'><div class='my-usr-msg voice-note'>";
                    echo "<p class='shared-music'><audio src='" . $message . "' type='audio/wav' controls></audio></p>";
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 1 && $row['type'] == 'user_media_location') {
					echo "<div class='other-msgs'><div class='other-usr-msg'>";
                    echo "<div class='map-msg-cover'></div><div class='map-msg' num='".$map_i."' lat='".number_format($message["lat"], 10)."' lng='".number_format($message["lng"], 10)."' acc='".$message["acc"]."'></div>";
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
					$map_i++;
                } elseif ($row['from_id'] != $user_id && chatType($chat_id) == 0  && $row['type'] == 'user_media_location') {
                    echo "<div class='other-msgs'><div class='username'>" . $row['from_name'] . "</div><div class='other-usr-msg'>";
                    echo "<div class='map-msg-cover'></div><div class='map-msg' num='".$map_i."' lat='".number_format($message["lat"], 10)."' lng='".number_format($message["lng"], 10)."' acc='".$message["acc"]."'></div>";
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
					$map_i++;
                } elseif ($row['from_id'] == $user_id && $row['type'] == 'user_media_location') {
                    echo "<div class='my-msgs'><div class='my-usr-msg'>";
					echo "<div class='map-msg-cover'></div><div class='map-msg' num='".$map_i."' lat='".number_format($message["lat"], 10)."' lng='".number_format($message["lng"], 10)."' acc='".$message["acc"]."'></div>";
                    echo "</div><div class='msg-time left'>" . date('H:i', $row['time']) . "</div><i class='material-icons right msg-icon tiny'>done</i></div>";
					$map_i++;
                } elseif ($row['type'] == 'system') {
                    /**
					* System Message
                    */
					echo "<div class='system'>" . $message.'</div>';
                }
                echo "</div>";
            }
			/**
			* Delete read messages
            */
            $delete = db_query("DELETE FROM `chat_unread` WHERE `usr_id` = '$user_id' && `chat_room` = '$chat_id'");
        }
    }
}

// User's chat list
function rooms()
{
    $user_id = db_escape(user_id()); // User ID
    $user_name = db_escape(user_name()); // Username

    $pp_query = db_query("SELECT `profile_pic` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $pp_row = mysqli_fetch_assoc($pp_query);
    $pp = $pp_row['profile_pic'];

    $query = db_query("SELECT `chat_room`, `last_message_time` FROM `chat_members` WHERE `user_id` = '$user_id' && (`status` = 1 || `status` = 2 || `status` = 4 || `status` = 6) ORDER BY `last_message_time` DESC"); // Gets chat ID

    echo "<div class='card-content mainw'>";

    if (enable_online_users() == 1) {
        online_users($user_id);
    }

    if (enable_friend_system() == 1) {
        friends($user_id);
    }

    user_settings($user_id);

    echo "<div class='card-content valign main-window'>";

    if (mysqli_num_rows($query) == 0) {
        // Check if user does not have any conversation
        echo "<div class='row not-any'><div class='col s12'><div class='card teal lighten-2'><div class='card-content white-text'><p>You do not have any conversations.</p></div></div></div></div>";
        echo "<div id='pmlist'>";
        echo "<ul class='collection msgs hide'></ul>";
        echo "</div>";
    } else {
        echo "<div class='row not-any hide'><div class='col s12'><div class='card teal lighten-2'><div class='card-content white-text'><p>You do not have any conversations.</p></div></div></div></div>";
        echo "<div id='pmlist'>";
        echo "<ul class='collection msgs'>";

        while ($row = mysqli_fetch_array($query)) {
            $zrow = $row['chat_room']; // Chat ID
            $nquery = db_query("SELECT `chat_name`, `id_hash`, `type`, `last_message_time`, `last_message` FROM `chat_room` WHERE `ID` = '$zrow' LIMIT 1");

            while ($nrow = mysqli_fetch_assoc($nquery)) {

                    /**
					* Convert user1|user2 chat title to only user1 or user2 depanding who you are.
                    *
					* E.g. You are user1 and other person is user2. Your chat title is user2 but the other person's title is user1.
					*/
					
                    if ($nrow['type'] == 1) {
                        $name = explode('|', $nrow['chat_name']);

                        if ($user_name == $name[0]) {
                            $cname = $name[1];
                        } else {
                            $cname = $name[0];
                        }

                        $img_query = db_query("SELECT `profile_pic` FROM `members` WHERE `username` = '$cname' LIMIT 1");
                        $img_result = mysqli_fetch_assoc($img_query);
                        $img = $img_result['profile_pic'];

                        if (empty($img)) {
                            $photo = "<i id='chat-main' class='z-depth-1 material-icons circle chat-list-photo grey lighten-2'>person</i><img id='chat-img-main' class='z-depth-1 circle hide chat-list-photo' width='66' height='66' src=''>";
                        } else {
                            $photo = "<i id='chat-main' class='z-depth-1 material-icons circle chat-list-photo grey lighten-2 hide'>person</i><img id='chat-img-main' class='z-depth-1 circle chat-list-photo' width='66' height='66' src='" . picture_destination().$img . "'>";
                        }
                    } else {
                        $cname = $nrow['chat_name']; // Converted Chat Name

                        $img_query = db_query("SELECT `group_pic` FROM `chat_room` WHERE `ID` = '$zrow' LIMIT 1");
                        $img_result = mysqli_fetch_assoc($img_query);
                        $img = $img_result['group_pic'];

                        if (empty($img)) {
                            $photo = "<i id='chat-main' class='z-depth-1 material-icons circle chat-list-photo grey lighten-2'>group</i><img id='chat-img-main' class='z-depth-1 circle hide chat-list-photo' width='66' height='66' src=''>";
                        } else {
                            $photo = "<i id='chat-main' class='z-depth-1 material-icons circle chat-list-photo grey lighten-2 hide'>group</i><img id='chat-img-main' class='z-depth-1 circle chat-list-photo' width='66' height='66' src='" . picture_destination().$img . "'>";
                        }
                    }

                $unread_q = db_query("SELECT `ID` FROM `chat_unread` WHERE `chat_room` = '$zrow' && `usr_id` = '$user_id'"); // Gets unread messages
                    $unread_num = mysqli_num_rows($unread_q);

                if (empty($nrow['last_message'])) {
                    // If Message is Empty, Leave a Space(For Design)
                    $last_message = '<br>';
                } else {
                    $last_message = stripslashes(ban_word(preg_replace("/\:\:e\|\|(.*?)\:\:/", "<img ondragstart='return false;' alt='&#x$1' src='./include/web-imgs/blank.jpg' style='background-image: url(\"./include/web-imgs/emojis.png\");' class='emoji-link-small sprite sprite-$1' draggable='false' />", $nrow['last_message'])));
                }
                if (userInRoom($user_id, $zrow)) {
                    if ($unread_num == 0) {
                        // If there is not any unread messages
                        echo "<li id='" . keymaker($zrow) . "' class='collection-item avatar waves-effect'>" . $photo . "<span class='title truncate'>" . html_entity_decode($cname) . "</span><div class='last_message truncate'>" . html_entity_decode($last_message) . "</div><div class='last_message_time'>" . date('H:i', $nrow['last_message_time']).'</div></li>';
                    } else {    // If there are unread messages
						if($unread_num > 1) {
							echo "<li id='" . keymaker($zrow) . "' class='collection-item avatar waves-effect'>" . $photo . "<span class='title truncate'>" . html_entity_decode($cname) . "</span><div class='last_message truncate'>" . html_entity_decode($last_message) . "</div><div class='last_message_time'>" . date('H:i', $nrow['last_message_time']) . "</div><span data-badge-caption='New Messages' class='new badge custom-badge " . $nrow['id_hash'] . "'>" . $unread_num.'</span></li>';
						} else {
							echo "<li id='" . keymaker($zrow) . "' class='collection-item avatar waves-effect'>" . $photo . "<span class='title truncate'>" . html_entity_decode($cname) . "</span><div class='last_message truncate'>" . html_entity_decode($last_message) . "</div><div class='last_message_time'>" . date('H:i', $nrow['last_message_time']) . "</div><span data-badge-caption='New Message' class='new badge custom-badge " . $nrow['id_hash'] . "'>" . $unread_num.'</span></li>';
						}
                    }
                } else {
                    echo "<li id='" . keymaker($zrow) . "' class='collection-item avatar waves-effect'>" . $photo . "<span class='title truncate'>" . html_entity_decode($cname) . "</span><div class='last_message truncate'>" . html_entity_decode($user_name) . " was kicked.</div><div class='last_message_time'>" . date('H:i', $row['last_message_time']).'</div></li>';
                }
            }
        }
        echo "</ul>";
        echo "</div>";
    }
    echo "</div>";
    echo "<div class='card-content search-res'></div>";
    echo "</div>";
}
    // User list in a chat room
function chat_users($username, $chat_id, $roomtype)
{
    $data = null;

    if ($roomtype == 0) {
        // Chat Type -> 0 // 0 -> Group Conversation // 1 -> Personal Message
        $query = db_query("SELECT `user_id`, `user_name` FROM `chat_members` WHERE `chat_room` = '$chat_id' && (`status` = 1 || `status` = 2) ORDER BY `user_name` ASC");
    } else {    
	    // Type -> 1
        $query = db_query("SELECT `user_id`, `user_name` FROM `chat_members` WHERE `chat_room` = '$chat_id' ORDER BY `user_name` ASC");
    }

    if (chat_owner($chat_id) == $username && $roomtype == 0) {
        // If the user is Admin and the room type is group conversation, add invite button
        $data .= "<div class='invite fixed-action-button'><a class='btn-floating btn-large waves-effect waves-light red invite-btn'><i class='material-icons clickable'>add</i></a></div>";
    }

    $data .=  "<ul class='collection user-list'>";
    while ($row = mysqli_fetch_array($query)) {
        $chat_userid = $row[0];
        $chat_username = $row[1];

        $query2 = db_query("SELECT `profile_pic` FROM `members` WHERE `ID` = '$chat_userid' LIMIT 1");

        $result = mysqli_fetch_array($query2);
        $pic = $result[0];

        $data .=  "<li class='collection-item avatar users' attr-id='" . $chat_userid . "'>";
        if (empty($pic)) {
            $data .= "<i id='chat-main-rev-user' class='z-depth-1 material-icons circle grey lighten-2 chat-list-photo'>person</i>";
            $data .= "<img id='chat-img-main-rev-user' class='z-depth-1 circle hide chat-list-photo' src=''>";
        } else {
            $data .= "<i id='chat-main-rev-user' class='z-depth-1 material-icons circle chat-list-photo grey lighten-2 hide'>person</i>";
            $data .= "<img id='chat-img-main-rev-user' class='z-depth-1 circle chat-list-photo' src='" . picture_destination().$pic . "'>";
        }
		if ($username != $chat_username && $roomtype == 0) {
			$data .=  "<span class='title truncate pm clickable' attr-id='".$chat_userid."'>".$chat_username.'</span>';
		} else {
			$data .=  "<span class='title truncate'>".$chat_username.'</span>';
		}
		
		if(user_id() == $chat_userid) {
			$data .= "<p class='green-text text-darken-2'>Online</p>";
		} else {
			if(isOnline($chat_userid)) {
				$data .= "<p id='online-status-all' attr-id='".$chat_userid."' class='green-text text-darken-2'>Online</p>";
			} else {
				$data .= "<p id='online-status-all' attr-id='".$chat_userid."' class='red-text text-darken-2'>Offline</p>";
			}
		}
        if ($username != $chat_username && chat_owner($chat_id) == $username && $roomtype == 0) {
            $data .= "<a href='#' style='margin-top:15px' attr-id='".$chat_userid."' class='kick secondary-content'><i class='material-icons clickable'>cancel</i></a>";
        }
        $data .= '</li>';
    }

    $data .=  '</ul>';

    return $data;
}

// Owner of the chat room
function chat_owner($chat_id)
{
    $result = db_query("SELECT `owner_name` FROM `chat_room` WHERE `ID` = '$chat_id' LIMIT 1");
    $row = mysqli_fetch_assoc($result);

    return $row['owner_name'];
}

// Checks if the user was kicked from the conversation
function isKicked($chat_id, $user_id)
{
    $query = db_query("SELECT `ID` FROM `chat_members` WHERE `user_id` = '$user_id' && `chat_room` = '$chat_id' && `status` = 4 LIMIT 1");

    if (mysqli_num_rows($query) == 1) {
        return true;
    } else {
        return false;
    }
}

// Typing Message input and button HTML codes
function sendMessageHTML()
{
    echo "<div class='blue-grey white-text msg-box'>";
    echo "<div class='input-field send-message col s12'>";
    if (enable_emoji() == 1) {
        echo "<a class='btn btn waves-effect waves-light' href='#' id='open-emoji'><i class='material-icons clickable' style='font-size:30px;'>tag_faces</i></a>";
        echo "<a class='btn btn waves-effect waves-light hide' href='#' id='close-emoji'><i class='material-icons' style='font-size:30px clickable'>keyboard_arrow_down</i></a>";
        echo "<div class='send-msg-bg' draggable='false'>Type a Message</div>";
        echo "<div spellcheck='true' contenteditable='true' id='send-msg' class='send-msg'></div>";
    } else {
        echo "<div class='send-msg-bg' style='left:30.5px!important' draggable='false'>Type a Message</div>";
        echo "<div spellcheck='true' style='margin-left:20px!important' contenteditable='true' id='send-msg' class='send-msg'></div>";
    }
    
    
	if(voice_notes() == 1) {
		echo "<a class='btn-floating btn waves-effect waves-light red hide' id='send-btn'><i class='material-icons send-btn clickable'>send</i></a>";
		echo "<a class='btn-floating btn waves-effect waves-light red' id='voice-btn'><i class='material-icons voice-btn clickable'>mic</i></a>";
		echo "<a class='btn-floating btn waves-effect waves-light green hide' id='voice-confirm-btn'><i class='material-icons voice-btn clickable'>done</i></a>";
		echo "<a class='btn-floating btn waves-effect waves-light red hide' id='voice-reset-btn'><i class='material-icons voice-btn clickable'>clear</i></a>";
		echo "<div class='hide' id='voice-recording'><span id='voice-recording-animation' class='z-depth-1'></span><span id='voice-recording-text'>00:00:00</span></div>";
	} else {
		echo "<a class='btn-floating btn waves-effect waves-light red' id='send-btn'><i class='material-icons send-btn clickable'>send</i></a>";
	}

    echo "</div>";
    echo "</div>";
}

// Creating group page design
function group_reveal()
{
    echo "<div class='card-reveal'>";
        echo "<span class='card-title grey-text text-darken-4'>Create a New Group</span>";
        echo "<ul id='dropdown6' class='dropdown-content'>";
            echo "<li><a href='#' id='create-upload-photo'>Upload Photo</a></li>";
            echo "<input type='file' accept='image/*' id='create-img' style='display:none;' />";
            echo "<div id='c-grp-img-remove' class='hide'>";
                echo "<li class='divider'></li>";
                echo "<li><a href='#' id='create-remove-photo'>Remove Photo</a></li>";
            echo "</div>";
        echo "</ul>";
        echo "<div class='create-group-pp-div'>";
            echo "<a class='dropdown-button' href='#' id='cdropdown6'><div class='create-group-pp'>Change Group Photo</div></a>";
            echo "<i id='c-group' class='z-depth-1 material-icons circle big-pp large grey lighten-2'>group</i>";
            echo "<img id='c-group-img' class='z-depth-1 circle hide' width='175' height='175' src=''>";
        echo "</div>";

        echo "<div class='row' style='margin-top:15px;'>";
            echo "<div class='col s12'>";
                echo "<div class='card teal lighten-2'>";
                    echo "<div class='card-content white-text'>";
                        echo "<div class='input-field grpname'>";
                            echo "<input id='grp' type='text' style='border-bottom:1px solid #fff!important;' id='grp-name' class='validate'>";
                            echo "<label for='grp' style='color:#fff!important;'>Group Name</label>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
        echo "<li class='divider'></li>";
        echo "<div class='row' style='margin-top:15px;'>";
            echo "<div class='col s12'>";
                echo "<div class='card teal lighten-2'>";
                    echo "<div class='card-content white-text'>";
                        echo "<div class='input-field'>";
                            echo "<input id='grp-s' type='text' style='border-bottom:1px solid #fff!important;' class='validate'>";
                            echo "<label for='grp-s' style='color:#fff!important;'>Search a User</label>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
        echo "<li class='divider'></li>";
        echo "<div class='grp-users' style='margin-top:15px;'><div class='chip'>You</div><div class='right capacity'>1/" . max_group_capacity().'</div></div>';
        echo "<li class='divider'></li>";
        echo "<div class='grp-search-content' style='margin-top:15px;display:none;'></div>";
        echo "<li class='divider'></li>";
        echo "<a class='waves-effect waves-light btn-large' id='create-group' style='margin-top:15px;'><i class='material-icons right clickable'>send</i>Create</a>";
    echo "</div>";
}

// Design of Invite Page
function invite_reveal()
{
    echo "<div class='invite-reveal'>";
        echo "<span class='card-title grey-text text-darken-4'>Invite More People</span>";
        echo "<div class='row' style='margin-top:15px;'>";
            echo "<div class='col s12'>";
                echo "<div class='card teal lighten-2'>";
                    echo "<div class='card-content white-text'>";
                        echo "<div class='input-field'>";
                            echo "<input id='inv-s' type='text' style='border-bottom:1px solid #fff!important;' class='validate'>";
                            echo "<label for='inv-s' style='color:#fff!important;'>Search a User</label>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
        echo "<li class='divider'></li>";
        echo "<div class='inv-users' style='margin-top:15px;'><div class='chip'>You</div><div class='right inv-capacity'>1/" . max_group_capacity().'</div></div>';
        echo "<li class='divider'></li>";
        echo "<div class='inv-search-content' style='margin-top:15px;display:none;'></div>";
        echo "<li class='divider'></li>";
        echo "<a class='waves-effect waves-light btn-large' id='invite-btn' style='margin-top:15px;'><i class='material-icons right clickable'>send</i>Invite</a>";
    echo "</div>";
}

// Loading Animation
function loading($id)
{
    echo "<div id='".$id."' class='z-depth-2 grey lighten-3 circle loading valign-wrapper'>";
        echo "<div class='preloader-wrapper active valign loading-content'>";
            echo "<div class='spinner-layer spinner-blue'>";
                echo "<div class='circle-clipper left'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
                echo "<div class='gap-patch'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
                echo "<div class='circle-clipper right'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
            echo "</div>";
            echo "<div class='spinner-layer spinner-red'>";
                echo "<div class='circle-clipper left'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
                echo "<div class='gap-patch'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
                echo "<div class='circle-clipper right'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
            echo "</div>";
            echo "<div class='spinner-layer spinner-yellow'>";
                echo "<div class='circle-clipper left'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
                echo "<div class='gap-patch'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
                echo "<div class='circle-clipper right'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
            echo "</div>";
            echo "<div class='spinner-layer spinner-green'>";
                echo "<div class='circle-clipper left'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
                echo "<div class='gap-patch'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
                echo "<div class='circle-clipper right'>";
                    echo "<div class='circle'></div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
}

// Chat Page Design
function chat_reveal()
{
    echo "<div class='chat-reveal'>";
        echo "<div class='z-depth-1 teal darken-1 chat-custom-nav'>";
    echo "<ul id='dropdown3' class='dropdown-content'></ul>";
    echo "<i id='chat-main-rev' class='z-depth-1 material-icons circle medium pp grey lighten-2 left valign'>person</i>";
    echo "<img id='chat-img-main-rev' class='z-depth-1 circle hide left pp valign' width='58' height='58' src=''>";
	echo "<div class='chat-name-wrapper'>";
		echo "<h5 class='chat-nav-un truncate'></h5>";
		echo "<p id='online-status'></p>";
	echo "</div>";
    echo "<ul class='right'>";
    echo "<a class='dropdown-button btn-flat custom-wave back-btn' href='#'><i class='material-icons custom-icon clickable'>arrow_back</i></a>";

    if (share_photo() == 1 || share_video() == 1 || share_file() == 1 || share_music() == 1 || share_location() == 1) {
        echo "<div class='fixed-action-btn vertical click-to-toggle'>";
        echo "<a class='dropdown-button btn-flat custom-wave'>";
        echo "<i class='material-icons share-btn clickable'>attach_file</i>";
        echo "</a>";
        echo "<ul>";
        if (share_photo() == 1) {
            echo "<li><div class='share-hover photo-hover hide'>Photo</div><a id='share-photo' class='btn-floating red'><i class='material-icons clickable'>photo</i></a></li>";
            echo "<form name='share_photo_form' id='share_photo_form' enctype='multipart/form-data'>";
            echo "<input class='share-photo-files hide' name='share-photo[]' type='file' accept='";
            for ($i = 0; $i < count(photo_extensions()); $i++) {
                echo "." . photo_extensions()[$i];
                if ($i != count(photo_extensions()) - 1) {
                    echo ",";
                }
            }
            echo "' multiple='multiple'>";
            echo "<input class='share-photo-token hide' name='share-photo-token' type='text'>";
            echo "<input class='share-photo-username hide' name='share-photo-username' type='text'>";
            echo "<input class='share-photo-userid hide' name='share-photo-userid' type='text'>";
            echo "<input class='share-photo-room hide' name='share-photo-room' type='text'>";
            echo "</form>";
        }
        if (share_video() == 1) {
            echo "<li><div class='share-hover video-hover hide'>Video</div><a id='share-video' class='btn-floating orange darken-3'><i class='material-icons clickable'>movie</i></a></li>";
            echo "<form name='share_video_form' id='share_video_form' enctype='multipart/form-data'>";
            echo "<input class='share-video-input hide' name='share-video[]' type='file' accept='";
            for ($i = 0; $i < count(video_extensions()); $i++) {
                echo "." . video_extensions()[$i];
                if ($i != count(video_extensions()) - 1) {
                    echo ",";
                }
            }
            echo "' multiple='multiple'>";
            echo "<input class='share-video-token hide' name='share-video-token' type='text'>";
            echo "<input class='share-video-username hide' name='share-video-username' type='text'>";
            echo "<input class='share-video-userid hide' name='share-video-userid' type='text'>";
            echo "<input class='share-video-room hide' name='share-video-room' type='text'>";
            echo "</form>";
        }
        if (share_file() == 1) {
            echo "<li><div class='share-hover file-hover hide'>File</div><a id='share-file' class='btn-floating green'><i class='material-icons clickable'>insert_drive_file</i></a></li>";
            echo "<form name='share_file_form' id='share_file_form' enctype='multipart/form-data'>";
            echo "<input class='share-file hide' name='share-file[]' type='file' accept='";
            for ($i = 0; $i < count(file_extensions()); $i++) {
                echo "." . file_extensions()[$i];
                if ($i != count(file_extensions()) - 1) {
                    echo ",";
                }
            }
            echo "' multiple='multiple'>";
            echo "<input class='share-file-token hide' name='share-file-token' type='text'>";
            echo "<input class='share-file-username hide' name='share-file-username' type='text'>";
            echo "<input class='share-file-userid hide' name='share-file-userid' type='text'>";
            echo "<input class='share-file-room hide' name='share-file-room' type='text'>";
            echo "</form>";
        }
        if (share_music() == 1) {
            echo "<li><div class='share-hover music-hover hide'>Music</div><a id='share-music' class='btn-floating blue'><i class='material-icons clickable'>headset</i></a></li>";
            echo "<form name='share_music_form' id='share_music_form' enctype='multipart/form-data'>";
            echo "<input class='share-music-input hide' name='share-music' type='file' accept='";
            for ($i = 0; $i < count(music_extensions()); $i++) {
                echo "." . music_extensions()[$i];
                if ($i != count(music_extensions()) - 1) {
                    echo ",";
                }
            }
            echo "'>";
            echo "<input class='share-music-token hide' name='share-music-token' type='text'>";
            echo "<input class='share-music-username hide' name='share-music-username' type='text'>";
            echo "<input class='share-music-userid hide' name='share-music-userid' type='text'>";
            echo "<input class='share-music-room hide' name='share-music-room' type='text'>";
            echo "</form>";
        }
        if (share_location() == 1) {
            echo "<li><div class='share-hover location-hover hide'>Location</div><a id='share-location' class='btn-floating brown'><i class='material-icons clickable'>location_on</i></a></li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    echo "<a class='dropdown-button btn-flat custom-wave chat-details' href='#' id='cdropdown3'><i class='material-icons custom-icon clickable'>more_vert</i></a>";
    echo "<a class='dropdown-button btn-flat custom-wave close-rev-inv hide' href='#'><i class='material-icons custom-icon clickable'>close</i></a>";
    echo "</ul>";
    echo "</div>";
    echo "<div id='msg-content'></div>";

    loading("loading");
    if (enable_emoji() == 1) {
        emoji_table();
    }
    sendMessageHTML();
    echo "</div>";
}

// Search User Elements
function search_user($stat = 1)
{
	switch($stat) {
		case 1:
			$type = 1;
			break;
		case 0:
			$type = 0;
			break;
		default:
			$type = 1;
			break;
	}
	
	if($type == 1) {
		echo "<div class='new-msg hide'>";
	} else {
		echo "<div class='new-msg'>";
	}
    
    echo "<div class='row'>";
    echo "<div class='input-field'>";
    echo "<input id='search' type='text' class='search'>";
    echo "<label for='search' style='color:white!important;'>Search a User</label>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

// Search User Elements
function search_user_to_add_friend($stat = 1)
{
	switch($stat) {
		case 1:
			$type = 1;
			break;
		case 0:
			$type = 0;
			break;
		default:
			$type = 1;
			break;
	}
	
	if($type == 1) {
		echo "<div class='new-friend hide'>";
	} else {
		echo "<div class='new-friend'>";
	}
    
    echo "<div class='row'>";
    echo "<div class='input-field'>";
    echo "<input id='search_friend' type='text' class='search'>";
    echo "<label for='search_friend' style='color:white!important;'>Search a User</label>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

// Header Design
function header_design()
{
	
    $user_id = db_escape(user_id()); // User ID
    $isGuest = isGuest($user_id);
    echo "<div class='z-depth-1 teal darken-1 card custom-nav'>";
    echo "<ul id='dropdown1' class='dropdown-content'>";
    echo "<li><a href='#' id='user-settings'>Profile</a></li>";
    echo "<li class='divider'></li>";
    echo "<li><a href='action.php?act=logout&v=" . time() . "'>Logout</a></li>";
    echo "</ul>";
    if ((guest_login() == 1 && guest_groups() == 1 && guest_pm() == 1 && $isGuest == 1) || (user_groups() == 1 && user_pm() == 1 && $isGuest == 0)) {
        echo "<ul id='dropdown2' style='width:137px!important;'class='dropdown-content'>";
        if (enable_online_users() == 1) {
            echo "<li><a href='#' id='user-list-btn'>User List</a></li>";
            echo "<li class='divider'></li>";
        }
        echo "<li><a href='#' id='new-msg'>New Chat</a></li>";
        echo "<li class='divider'></li>";
        echo "<li><a href='#' id='new-grp'>New Group</a></li>";
        echo "</ul>";
    }
    if ((guest_pm() == 1 && $isGuest == 1) || (user_pm() == 1 && $isGuest == 0)) {
        search_user(1);
    }
	if (enable_friend_system() == 1 && (isGuest(user_id()) == 0 || (isGuest(user_id()) == 1 && guest_friends() == 1))) {
		search_user_to_add_friend(1);
	}

    $pp_query = db_query("SELECT `profile_pic` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $pp_row = mysqli_fetch_assoc($pp_query);
    $pp = $pp_row['profile_pic'];
	
    echo "<div class='nav-img valign-wrapper'>";
    if (empty($pp)) {
        echo "<i id='pp-main' class='valign material-icons circle medium pp grey lighten-2 z-depth-1 left'>person</i>";
        echo "<img id='pp-img-main' class='valign circle hide left pp z-depth-1' width='58' height='58' src=''>";
    } else {
        echo "<i id='pp-main' class='valign material-icons circle medium pp grey lighten-2 left hide z-depth-1'>person</i>";
        echo "<img id='pp-img-main' class='valign circle left pp z-depth-1' width='58' height='58' src='" . picture_destination().$pp . "'>";
    }
    echo "</div>";
    echo "<h5 class='nav-un truncate'>" . user_name().'</h5>';
    echo "<ul class='right'>";
    echo "<a class='dropdown-button btn-flat custom-wave clear-btn' style='display:none;' href='#'><i class='material-icons custom-icon clickable'>clear</i></a>";
    echo "<a class='dropdown-button btn-flat custom-wave clear-btn-friend' style='display:none;' href='#'><i class='material-icons custom-icon clickable'>clear</i></a>";
	echo "<a class='btn-flat custom-wave close-rev' style='display:none;'><i class='material-icons custom-icon clickable'>close</i></a>";
    if ((guest_groups() == 0 && $isGuest == 1) || (user_groups() == 0 && $isGuest == 0)) {
        echo "<a class='dropdown-button btn-flat custom-wave' id='new-msg' href='#'><i class='material-icons custom-icon clickable'>add</i></a>";
    } elseif ((guest_pm() == 0 && $isGuest == 1) || (user_pm() == 0 && $isGuest == 0)) {
        echo "<a class='dropdown-button btn-flat custom-wave' id='new-grp' href='#'><i class='material-icons custom-icon clickable'>add</i></a>";
    } else {
        echo "<a class='dropdown-button btn-flat custom-wave' id='add-btn' href='#'><i class='material-icons custom-icon clickable'>add</i></a>";
    }
	if(enable_friend_system($user_id) == 1 && isGuest($user_id) == 0 || (isGuest($user_id) == 1 && guest_friends() == 1)) {
		echo "<a id='friends-btn' class='btn-flat custom-wave tooltipped' data-position='bottom' data-delay='50' data-tooltip='Friend List'><i class='material-icons custom-icon clickable'>supervisor_account</i></a>";
	}
	if(isAdmin($user_id)) {
		echo "<a class='btn-flat custom-wave tooltipped' data-position='bottom' data-delay='50' data-tooltip='Admin Panel' href='./admin/'><i class='material-icons custom-icon clickable'>dashboard</i></a>";
	}
    echo "<a class='dropdown-button btn-flat custom-wave' href='#' id='cdropdown1'><i class='material-icons custom-icon clickable'>settings</i></a>";
    echo "</ul>";
    echo "</div>";
}

/**
* General Function
*
* Includes most of the things that are required to run the application
*
* 1 - Show Header
* 0 - Hide Header
*/
function chat_application($type = "full_page")
{
    check_config_values();
    switch ($type) {
        case "full_page";
            $stat = 1;
			break;
		case "existing_page";
            $stat = 2;
			break;
        default;
            $stat = 1;
			break;
    }
    $user_id = user_id();
    
    $query = db_query("SELECT `username`, `email`, `token` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $row = mysqli_fetch_array($query);
    $username = $row['username'];
    $email = $row['email'];
    $token = $row['token'];

    echo "<head>";
        meta_tags();
        css_files();
        js_variables();
        javascript_files();
    echo "</head>";
	if(blockUsers()) {
		echo "<div class='content'>";
			echo "<div class='bg-top'></div>";
			echo "<div class='bg-bottom'></div>";
			echo "<div class='row'>";
				echo "<div class='col s12 m6'>";
					echo "<div class='card blue-grey darken-1 confirmation-card' style='top:30%;'>";
						echo "<div class='card-content white-text'>";
							echo "<span class='card-title'>Suspended IP Address</span>";
							echo "<p>Your IP address has been banned.</p>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	} else {
		if (isUserLoggedIn()) {
			if($stat == 1) {
				echo "<div class='content'>";
					echo "<div class='bg-top'></div>";
					echo "<div class='bg-bottom'></div>";
			}
				if(!isUserActivated($user_id) && user_activation() == 1) {
					echo "<div class='content'>";
						echo "<div class='bg-top1'></div>";
						echo "<div class='bg-bottom'></div>";
						echo "<div class='row'>";
							echo "<div class='col s12 m6'>";
								echo "<div class='card blue-grey darken-1 confirmation-card' style='top:15%;'>";
									echo "<div class='card-content white-text'>";
										echo "<span class='card-title'>Account Activation is Required.</span>";
										echo "<p>Please check your email and activate your account.</p>";
										echo "<p>Haven't you received an Email from us? Click <a href='#' id='get_new_activation_code'>here</a> to get a new activation code.</p>";
										echo "<br><div class='divider'></div>";
										echo "<div class='row'>";
											echo "<div class='input-field col s12'>";
												echo "<input id='activation_code' type='text'>";
												echo "<label for='activation_code' style='color:white!important'>Activation Code</label>";
											echo "</div>";
											echo "<a class='col s12 m6 l3 waves-effect waves-light btn' id='activate-account-btn'>Activate</a>";
										echo "</div>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				} else {
					if(!isBanned($user_id)) {
						echo "<script type='text/javascript'>
							$( document ).ready( function() {
									$(window).on('beforeunload', function(){
										$.post('./action.php?act=offline', {userid: userid, username: username, token: token});
									});
									websocket.onopen = function (event) {
										var msg = {
										name: username,
										iduser: userid,
										token: token,
										ip_address: '".getRealIpAddr()."'
									};
									var json_msg = JSON.stringify( msg );
									websocket.send( json_msg );
								};
							});
						</script>";
						echo "<div id='modal1' class='modal bottom-sheet'>";
							echo "<div class='modal-content'>";
								echo "<h4>Users</h4>";
								echo "<p></p>";
							echo "</div>";
						echo "</div>";
						if(isGuest($user_id) == 0) {
							echo "<div id='modal11' class='modal grey lighten-3' style='text-align:center;'>";
								echo "<div class='modal-content'>";
									echo "<h5>Change Password</h5>";
									echo "<div class='row'><div class='input-field col s12'><input id='current_pass' type='password'><label for='current_pass'>Current Password</label></div></div>";
									echo "<div class='row'>";
										echo "<div class='input-field col s12'><input id='new_pass_1' type='password'><label for='new_pass_1'>New Password</label></div>";
										echo "<div class='input-field col s12'><input id='new_pass_2' type='password'><label for='new_pass_2'>Repeat New Password</label></div>";
									echo "</div>";
								echo "</div>";
								echo "<div class='modal-footer blue-grey'>";
									echo "<a id='change-password-save' class='white-text right modal-action waves-effect waves-grey btn-flat'>Save</a>";
									echo "<a id='change-password-cancel' class='white-text left modal-action modal-close waves-effect waves-grey btn-flat'>Cancel</a>";
								echo "</div>";
							echo "</div>";
						}
						if(share_photo() == 1) {
							echo "<div id='modal2' class='modal modal-fixed-footer grey lighten-3' style='text-align:center;'>";
								echo "<div class='modal-content'>";
									echo "<img class='share-img-preview responsive-img'><div class='share-img-list'></div>";
								echo "</div>";
								echo "<div class='modal-footer blue-grey'>";
									echo "<a id='share-upload' class='white-text right modal-action modal-close waves-effect waves-grey btn-flat'>Upload</a>";
									echo "<a id='share-cancel' class='white-text left modal-action modal-close waves-effect waves-grey btn-flat'>Cancel</a>";
								echo "</div>";
							echo "</div>";
						}
						if(share_location() == 1) {
							echo "<div id='modal51' class='modal modal-fixed-footer grey lighten-3' style='text-align:center;'>";
								echo "<div class='modal-content'>";
									echo "<div id='modal-map'></div>";
								echo "</div>";
								echo "<div class='modal-footer blue-grey'>";
									echo "<a id='share-location-btn' class='white-text right modal-action modal-close waves-effect waves-grey btn-flat disabled'>Send</a>";
									echo "<a id='share-location-cancel' class='white-text left modal-action modal-close waves-effect waves-grey btn-flat'>Cancel</a>";
								echo "</div>";
							echo "</div>";
						}
						echo "<div id='modal52' class='modal modal-fixed-footer grey lighten-3' style='text-align:center;'>";
							echo "<div class='modal-content'>";
								echo "<div id='big-modal-map'></div>";
							echo "</div>";
							echo "<div class='modal-footer blue-grey'>";
								echo "<a class='white-text left modal-action modal-close waves-effect waves-grey btn-flat'>Close</a>";
							echo "</div>";
						echo "</div>";
						
						if(share_video() == 1) {
							echo "<div id='modal3' class='modal modal-fixed-footer grey lighten-3' style='text-align:center;'>";
								echo "<div class='modal-content'>";
									echo "<div class='video-file'></div><div class='share-video-list'></div>";
								echo "</div>";
								echo "<div class='modal-footer blue-grey'>";
									echo "<a id='video-upload' class='white-text right modal-action modal-close waves-effect waves-grey btn-flat'>Upload</a>";
									echo "<a id='video-cancel' class='white-text left modal-action modal-close waves-effect waves-grey btn-flat'>Cancel</a>";
								echo "</div>";
							echo "</div>";
						}
						if(share_file() == 1) {
							echo "<div id='modal4' class='modal modal-fixed-footer grey lighten-3' style='text-align:center;'>";
								echo "<div class='modal-content'>";
									echo "<div class='file-file'></div><div class='share-file-list'></div>";
								echo "</div>";
								echo "<div class='modal-footer blue-grey'>";
									echo "<a id='file-upload' class='white-text right modal-action modal-close waves-effect waves-grey btn-flat'>Upload</a>";
									echo "<a id='file-cancel' class='white-text left modal-action modal-close waves-effect waves-grey btn-flat'>Cancel</a>";
								echo "</div>";
							echo "</div>";
						}
						if(share_music() == 1) {
							echo "<div id='modal5' class='modal modal-fixed-footer grey lighten-3' style='text-align:center;'>";
								echo "<div class='modal-content'>";
									echo "<div class='music-file'></div><div class='share-music-list'></div>";
								echo "</div>";
								echo "<div class='modal-footer blue-grey'>";
									echo "<a id='music-upload' class='white-text right modal-action modal-close waves-effect waves-grey btn-flat'>Upload</a>";
									echo "<a id='music-cancel' class='white-text left modal-action modal-close waves-effect waves-grey btn-flat'>Cancel</a>";
								echo "</div>";
							echo "</div>";
						}
						echo "<div class='valign-wrapper main-content'>";
							echo "<div class='row custom-row'>";
								echo "<div class='custom-row-col col s12 full-height'>";
									echo "<div class='card full-height'>";
										
										if($stat == 1) {
											header_design();
											echo "<div class='custom-cont'>";
												rooms();
											echo "</div>";
										} else {
											echo "<div class='custom-cont2'>";
												rooms();
											echo "</div>";
										}

										group_reveal();
										chat_reveal();
										invite_reveal();

									echo "</div>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					} else {
						$uid = user_id();
						$check_ban1 = db_query("SELECT `time`, `reason` FROM `chat_banned_users` WHERE `userid` = '$uid' && `type` = 'temporary' ORDER BY `time` DESC LIMIT 1");
						$check_ban2 = db_query("SELECT `reason` FROM `chat_banned_users` WHERE `userid` = '$uid' && `type` = 'permanent' LIMIT 1");
						$get_res1 = mysqli_fetch_array($check_ban1);
						$get_res2 = mysqli_fetch_array($check_ban2);
						echo "<div class='row'>";
							echo "<div class='col s12 m6'>";
								echo "<div class='card blue-grey darken-1 confirmation-card'>";
									echo "<div class='card-content white-text'>";
										echo "<span class='card-title'>Suspended Account</span>";
										if(mysqli_num_rows($check_ban2) > 0) {
											echo "<p>Your account has been permanently suspended.</p>";
											if(!empty($get_res2[0])) {
												echo "<br><p>Reason: " . $get_res2[0] . "</p>";
											}
										} elseif(mysqli_num_rows($check_ban1) > 0 && $get_res1[0] > time()) {
											echo "<p>Your account has been temporarily suspended until ".date("d/m/Y H:i", $get_res1[0]).".</p>";
											if(!empty($get_res1[1])) {
												echo "<br><p>Reason: " . $get_res1[1] . "</p>";
											}
										} else {
											header("location: ./index.php");
											exit;
										}
									echo "</div>";
									echo "<div class='card-action'>";
										echo "<a href='./action.php?act=logout&v=" . time() . "&token=" . $_COOKIE["login_token"] . "'>Logout</a>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					}
				}
			if($stat == 1) {
				echo "</div>";
			}
		} else {
			// if($stat == 1) {
			// 	echo "<div class='content'>";
			// 		echo "<div class='bg-top'></div>";
			// 		echo "<div class='bg-bottom'></div>";
			// 		login_register_design();
			// 	echo "</div>";
			// } else {
			// 	login_register_design();
			// }
		}
	}
}

function emoji_table()
{
    if (enable_emoji() == 1) {
        echo "<div id='emoji-table' style='display:none;'>";
            echo "<div class='row'>";
                echo "<ul class='tabs emoji-tab'>";
                    echo "<li class='tab col s1-2'><a href='#smile'><img ondragstart='return false;' draggable='false' class='emoji-title sprite sprite-1f642' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#x1f642' src='./include/web-imgs/blank.jpg' /></a></li>";
                    echo "<li class='tab col s1-2'><a href='#nature'><img ondragstart='return false;' draggable='false' class='emoji-title sprite sprite-1f43b' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#1f43b' src='./include/web-imgs/blank.jpg' /></a></li>";
                    echo "<li class='tab col s1-2'><a href='#food'><img ondragstart='return false;' draggable='false' class='emoji-title sprite sprite-1f37d' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#1f37d' src='./include/web-imgs/blank.jpg' /></a></li>";
                    echo "<li class='tab col s1-2'><a href='#travel'><img ondragstart='return false;' draggable='false' class='emoji-title sprite sprite-1f697' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#1f697' src='./include/web-imgs/blank.jpg' /></a></li>";
                    echo "<li class='tab col s1-2'><a href='#object'><img ondragstart='return false;' draggable='false' class='emoji-title sprite sprite-2699' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#2699' src='./include/web-imgs/blank.jpg' /></a></li>";
                    echo "<li class='tab col s1-2'><a href='#symbol'><img ondragstart='return false;' draggable='false' class='emoji-title sprite sprite-2764' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#2764' src='./include/web-imgs/blank.jpg' /></a></li>";
                    echo "<li class='tab col s1-2'><a href='#flag'><img ondragstart='return false;' draggable='false' class='emoji-title sprite sprite-1f3f4' style='background-image: url(\"./include/web-imgs/emojis.png\");' alt='&#1f3f4' src='./include/web-imgs/blank.jpg' /></a></li>";
                echo "</ul>";
            echo "</div>";
            echo "<div class='row emoji-row'>";
                echo "<div id='smile' class='col s12 emoji-list'>";
                    smile_emoji();
                echo "</div>";
                echo "<div id='nature' class='col s12 emoji-list'>";
                    nature_emoji();
                echo "</div>";
                echo "<div id='food' class='col s12 emoji-list'>";
                    food_emoji();
                echo "</div>";
                echo "<div id='travel' class='col s12 emoji-list'>";
                    travel_emoji();
                echo "</div>";
                echo "<div id='object' class='col s12 emoji-list'>";
                    object_emoji();
                echo "</div>";
                echo "<div id='symbol' class='col s12 emoji-list'>";
                    symbol_emoji();
                echo "</div>";
                echo "<div id='flag' class='col s12 emoji-list'>";
                    flag_emoji();
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }
}

function guest_confirmation($user_id, $username, $email, $password, $token)
{
    echo "<div class='row'>";
        echo "<div class='col s12 m6'>";
            echo "<div class='card blue-grey darken-1 confirmation-card'>";
                echo "<div class='card-content white-text'>";
                    echo "<span class='card-title'>Login Information</span>";
                    echo "<p>Keep these information to login later.</p><br>";
                    echo "<p><b>Username:</b> " . $username . "</p>";
                    echo "<p><b>E-Mail:</b> " . $email . "</p>";
                    echo "<p><b>Password:</b> " . $password . "</p>";
                echo "</div>";
                echo "<div class='card-action'>";
                    echo "<a id='btn-continue' href='#'>Continue</a>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";

    echo "<script>
            $('#btn-continue').on('click', function(ev) {
                ev.preventDefault();
                jQuery.ajax( {
                    type: 'POST',
                    url: './action.php?act=update-guest',
                    cache: false,
                    data:{ userid: '" . $user_id . "', token: '" . $token . "' },
                    success: function( response ) {
                        if( response == 0 ) {
                            Materialize.toast( 'Invalid token.', 4000 ); // 4000 is the duration of the toast
                        } else if( response == 1 ) {
                            window.location.href = './?v=" . time() . "';
                        }
                    },
                    error: function( e ){
                        Materialize.toast( 'An unknown error has occured.', 4000 );
                    }
                });
            });
    </script>";
}

function user_settings($user_id)
{
    $pp_query = db_query("SELECT `profile_pic` FROM `members` WHERE `ID` = '$user_id' LIMIT 1");
    $pp_row = mysqli_fetch_assoc($pp_query);
    $pp = $pp_row['profile_pic'];

    echo "<div class='card user-settings'>";
        echo "<ul id='dropdown4' class='dropdown-content'>";
            echo "<li><a href='#' id='upload-photo'>Upload Photo</a></li>";
            echo "<input type='file' accept='image/*' id='upload-pp' style='display:none;' />";
            if (empty($pp)) {
                echo "<div id='pp-act-div' class='hide'>";
            } else {
                echo "<div id='pp-act-div'>";
            }
                echo "<li class='divider'></li>";
                echo "<li><a href='#' id='remove-photo'>Remove Photo</a></li>";
            echo "</div>";
        echo "</ul>";
        echo "<span class='card-title grey-text text-darken-4'>Profile<i class='material-icons right close-user-settings clickable'>close</i></span>";
        echo "<div class='edit-pp'>";
            echo "<a class='dropdown-button' href='#' id='cdropdown4'><div class='change-pp'>Change Profile Photo</div></a>";
            if (empty($pp)) {
                echo "<i id='pp' class='z-depth-1 material-icons circle big-pp large grey lighten-2'>person</i>";
                echo "<img id='pp-img' class='z-depth-1 circle hide' width='175' height='175' src=''>";
            } else {
                echo "<i id='pp' class='z-depth-1 material-icons circle big-pp large grey lighten-2 hide'>person</i>";
                echo "<img id='pp-img' class='z-depth-1 circle' width='175' height='175' src='" . picture_destination() . $pp . "'>";
            }
        echo "</div>";
        echo "<ul id='save-pp-ul'><li><a id='save-pp' class='waves-effect waves-light btn'>Save</a><a id='discard-pp' class='waves-effect waves-light btn'>Discard</a></li></ul>";
		if(isGuest($user_id) == 0) {
			echo "<div class='change_password_wrapper'>";
				echo "<a href='#' id='change-password'>Change Password</a>";
			echo "</div>";
		}
		if(enable_user_status() == 1) {
			echo "<div class='group-name'>";
				echo "<p class='card-title'>Status:";
				echo "<a class='waves-effect waves-grey btn-flat circle edit-status-btns right' id='edit-status'><i class='material-icons circle clickable' style='font-size:1.6rem;'>edit</i></a>";
				echo "<a class='waves-effect waves-grey btn-flat circle edit-status-btns right hide' id='reset-status'><i class='material-icons circle clickable' style='font-size:1.6rem;'>close</i></a>";
				echo "<a class='waves-effect waves-grey btn-flat circle edit-status-btns right hide' id='confirm-status'><i class='material-icons circle clickable' style='font-size:1.6rem;'>done</i></a>";
				echo "</p>";
				echo "<p>";
					$user_status = user_status(user_id());
					if(empty($user_status)) {
						echo "<div id='user_status'>".get_option("DEFAULT_STATUS")."</div>";
					} else {
						echo "<div id='user_status'>".user_status(user_id())."</div>";
					}
					echo "<input type='text' id='user-status-input' class='hide'>";
				echo "</p>";
			echo "</div>";
		}
	echo "</div>";
    echo "<div class='card chat-settings'></div>";
}

function online_users($user_id)
{
    echo "<div class='card online-users'>";
    echo "<span class='card-title grey-text text-darken-4'><span id='online-user-num'>Online Users</span><i class='material-icons right clickable close-online-users'>close</i><a class='waves-effect waves-teal btn-flat refresh-list-a right'><i class='material-icons refresh-list clickable grey-text text-darken-4'>cached</i></a></span>";
		echo "<div class='online-user-list'></div>";
        echo "<div class='pagi'></div>";
		loading("online-loading");
    echo "</div>";
}

function friends($user_id)
{
    echo "<div class='card friends'>";
    echo "<span class='card-title grey-text text-darken-4'>Friends  <a class='waves-effect waves-teal btn-flat refresh-list-a'><i class='material-icons refresh-friend-list clickable grey-text text-darken-4'>cached</i></a>  <a class='waves-effect waves-teal btn-flat refresh-list-a' id='new-friend'><i class='material-icons clickable grey-text text-darken-4'>person_add</i></a><i class='material-icons right clickable close-friends'>close</i></span>";
    echo "<div class='friend-list'></div>";
    echo "<div class='pagi-friend'></div>";
	loading("friend-loading");
    echo "</div>";
}

function login_register_design()
{
    echo "<div class='custom-valign-wrapper full-height'>";
    echo "<div class='card login-card z-depth-2' id='login-reveal'>";
        echo "<div class='card-content'>";
            echo "<div class='card-title text-darken-4'>Login</div>";
            echo "<form method='post' action='./' name='loginform' autocomplete='off' enctype='multipart/form-data'>";									  
                echo "<div class='row'>";
                    echo "<div class='input-field custom-input-field col s12'>";
                        echo "<input id='login_input_email' name='email' type='email' autocomplete='off' required/>";
                        echo "<label for='login_input_email'>Username or Email</label>";
                    echo "</div>";
                echo "</div>";
                echo "<div class='row'>";
                    echo "<div class='input-field custom-input-field col s12'>";
                        echo "<input id='login_input_password' name='user_password' type='password' autocomplete='off' required/>";
                        echo "<label for='login_input_password'>Password</label>";
                    echo "</div>";
                echo "</div>";
                echo "<button class='btn login' type='submit' name='login'>Login</button>";
				if(forgot_password() == 1) {
					echo "<a class='btn forgot-password'>Forgot Password</a>";
				}
            echo "</form>";
        echo "</div>";
        echo "<div class='card-action'>";
            if (guest_login() == 1) {
                echo "<a href='#' class='register-btn left'>Register</a>";
                echo "<a href='#' class='guest-btn right'>Guest Login</a>";
            } else {
                echo "<a href='#' class='register-btn'>Register</a>";
			}
		echo "</div>";
	echo "</div>";

	echo "<div id='registration-reveal' class='card login-card z-depth-2'>";
        echo "<div class='card-content'>";
			echo "<div class='card-title text-darken-4'>Registration<i class='material-icons right close-register'>close</i></div>";
			echo "<form method='post' action='./' name='registerform' autocomplete='off' enctype='multipart/form-data'>";
				echo "<div class='row'>";
					echo "<div class='input-field col s12'>";
						echo "<input id='reg_username' name='reg_username' type='text' autocomplete='off' class='validate' />";
						echo "<label for='reg_username'>Username</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						echo "<input id='reg_email' name='reg_email' type='email' autocomplete='off' class='validate' />";
						echo "<label for='reg_email'>Email</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						echo "<input id='reg_password' name='reg_password' type='password' autocomplete='off' class='validate' />";
						echo "<label for='reg_password'>Password</label>";
					echo "</div>";
					echo "<div class='input-field col s12'>";
						echo "<input id='reg_password2' name='reg_password2' type='password' autocomplete='off' class='validate' />";
						echo "<label for='reg_password2'>Password (Repeat)</label>";
					echo "</div>";
				echo "</div>";
				echo "<div class='card-action' style='padding-bottom:0!important;'>";
					echo "<button class='btn register' type='submit' name='register'>Register</button>";
				echo "</div>";
			echo "</form>";
        echo "</div>";
    echo "</div>";
	
	if(forgot_password() == 1) {
		echo "<div id='password-reveal' class='card login-card z-depth-2'>";
			echo "<div class='card-content'>";
				echo "<div class='card-title text-darken-4'>Did you forget your password?<i class='material-icons right close-password'>close</i></div>";
				echo "<div class='row'>";
					echo "<div class='input-field col s12'>";
						echo "<input id='pass_email' name='pass_email' type='email' autocomplete='off' />";
						echo "<label for='pass_email'>Email</label>";
					echo "</div>";
					echo "<a class='btn' href='#' id='reset-password-btn'>Reset Password</a>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	}
    echo "</div>";
}
