<?php 

get_header();


$vars = array();

$test = get_term($term);

if (!empty($_POST['localisation']) || !empty($_POST['structure_name']) || !empty($_POST['thematique'])) {

    $vars = $_POST;
    $structure_name = null;
    if (isset($_POST['thematique']) && $_POST['thematique'] != "") {

        $vars['rubrique'] = $vars['thematique'];

        $categorie = $vars['rubrique'];

        $rubrique = get_term_by('slug', $categorie, 'category');

        $tax_query = array(array('taxonomy' => 'category', 'field' => 'term_id', 'terms' => $rubrique->term_id));

    }else{
        $vars['rubrique']=$_GET['rubrique']; 
    }

    if (isset($_POST['structure_name']) && $_POST['structure_name'] != "") {

        $structure_name = $_POST['structure_name'];

    }

    if (isset($_POST['localisation']) && ($_POST['localisation']) != "" && $_POST['localisation'] != NULL) {

        $ville = $_POST['localisation'];

        $meta_query = array('relation' => 'OR', array('key' => 'wpcf-adresse', 'value' => 'pas d\'accueil physique', 'compare' => 'LIKE'), array('key' => 'wpcf-ville', 'value' => $ville, 'compare' => 'LIKE'));

    }

    $categorie = $vars['rubrique'];

    $categoryList = get_categories(array('hide_empty' => 0, 'post_type' => 'structure', 'selected' => $categorie));

    $rubrique = get_term_by('slug', $categorie, 'category');
    
    $args = array(

        'posts_per_page' => -1,

        'post_type' => 'structure',

        's' => $structure_name,

        'meta_query' => $meta_query,

        'tax_query' => $tax_query

    );

    $posts = get_posts($args);

} else {

    $categorie = $_GET['rubrique'];

    $categoryList = get_categories(array('hide_empty' => 0, 'post_type' => 'structure', 'selected' => $categorie));

    $rubrique = get_term_by('slug', $categorie, 'category');
    
    $args = array(

        'posts_per_page' => -1,

        'post_type' => 'structure',

        'tax_query' => array(

            array(

                'taxonomy' => 'category',

                'field' => 'term_id',

                'terms' => $rubrique->term_id

            )

        ),

    );

    $posts = get_posts($args);

}

//Background de la catégorie de structure

$imageRubrique = types_render_termmeta("fond-categorie", array("term_id" => $rubrique->term_id, "raw" => true));

$bgImageRubrique = "";

if ($imageRubrique != "") {

    $data_img = wpcf_fields_image_get_data($imageRubrique);

    $img = wp_get_attachment_image_src($data_img['is_attachment'], 'aep-annuaire-bg');

    $bgImageRubrique = $img[0];

}

?>

<section id="annuaire-parentalite-fiche" >

    <button  id="open-map" class="pull-right btn btn-default close"><i class="material-icons pull-right">map</i></button>

    <div class="row">

        <div id="structure-cols" class="col-md-8 col-md-10 col-sm-12" style="background-image: linear-gradient(rgba(0,0,0,0.4),rgba(0,0,0,0.4)), url('<?php echo $bgImageRubrique; ?>')">

            <div class="scrollable-col slimScroll">

                <div class="container-results">

                    <a href="<?php echo home_url() ?>/annuaire-de-la-parentalite-en-haute-loire/" class="btn-retour-recherche"><i class="fa fa-arrow-left"></i>Revenir à la recherche</a>

                    <p class="zone-title">Annuaire de la parentalité en Haute-Loire <span>0>18 ans</span></p>

                    <h1 class="rubrique-title"><?php echo $rubrique->name; ?></h1>

                    <span class="underline"></span>

                    <p><?php echo types_render_termmeta("introduction-rubrique", array("term_id" => $rubrique->term_id, "raw" => true)); ?></p>

                    <span>Résultat: <?php echo count($posts); ?> résultats trouvés</span>

                    <div class="search-param">

                        <span>Mes critères</span>

                        <form method="post" >

                            <div class="row">

                                <div class="col-md-8">

                                    <div class="row">

                                        <?php

                                        foreach ($vars as $criteria => $value) {

                                            if ($value != "") {

                                                if ($criteria != 'thematique') {  

                                                    ?>

                                                    <div class="col-md-6">

                                                        <label>

                                                            <input type="checkbox" value="<?php echo $value; ?>" class="check" checked>

                                                            <?php echo $value; ?>

                                                        </label>

                                                    </div>

                                                    <?php

                                                }

                                            }

                                        }

                                        ?>

                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <a id="uncheck" class="uncheck-all pull-right">Tout déselectionner</a>

                                </div>

                            </div>

                            <hr>

                            <div class="row">



                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label>Localisation</label>

                                        <input id="localisation" type="text" class="form-control " name="localisation">
                                        
                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label>Nom de la structure</label>

                                        <input type="text" class="form-control" name="structure_name">

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label>Thématiques</label>

                                        <select class="form-control" name="thematique">

                                            <option value=""></option>

                                            <?php

                                            foreach ($categoryList as $theCategory) {

                                                echo "<option value='" . $theCategory->slug . "'>" . $theCategory->name . "</option>";

                                            }

                                            ?>

                                        </select>

                                    </div>

                                </div>
                                
                                <div class="col-md-3">

                                    <!--<div class="submit-button "><input type="submit" class="btn btn-light-blue" value="Affiner la recherche"/></div>-->
                                    <button class="submit-button btn btn-light-blue" type="submit" >Affiner la recherche</button>

                                </div>

                            </div>

                        </form>

                    </div>

                    <div class="search-result">

                        <ul>

                            <?php

                            foreach ($posts as $post) {

                                $thumbnail_infos = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'aep-actu-row');

                                ?>
                                <a data-toggle="collapse" data-target="#detail-<?php echo $post->ID ;?>">
                                <li class="result-line">

                                    <div class="row">
                                        
                                        <div class="line">

                                            <div class="col-md-8 col-xs-12">

                                                <h2><?php echo $post->post_title ?></h2>

                                                <p><?php echo types_render_field("introduction", array("raw" => true)) ?></p>

                                            </div>

                                            <div class="col-md-4 col-xs-12 actions">

                                                <div class="pull-right">

                                                    <?php if (types_render_field("ville", array("raw" => true)) !== "") { ?>

                                                        <button class="btn btn-light-blue" data-toggle="collapse" data-target="#detail-<?php echo $post->ID ;?>">

                                                            <?php echo strtoupper(types_render_field("ville", array("raw" => true))); ?>

                                                        </button>

                                                    <?php } ?>

                                                    <a data-toggle="collapse" data-target="#detail-<?php echo $post->ID ?>" class="expand-result">

                                                        <i class="fa fa-plus pull-right"></i>

                                                    </a>

                                                </div>

                                            </div>

                                        </div>                                        

                                    </div>

                                    <div class="row">

                                        <div id="detail-<?php echo $post->ID ?>" class="collapse content">

                                            <div class="col-md-6">

                                                <div class="blue-text">Objectif:</div>

                                                <div class="objectif">

                                                    <p><?php echo types_render_field("objectifs", array("raw" => true)) ?></p>

                                                </div>

                                                <a href="<?php echo the_permalink(); ?>?rubrique=<?php echo $rubrique->term_id ?>" class="btn btn-light-blue">En savoir plus</a>

                                            </div>

                                            <div class="col-md-6">

                                                <div class="blue-text">Contact:</div>

                                                <div class="contact">

                                                    <p>

                                                        <?php echo types_render_field("telephone", array("raw" => true)) ?><br>

                                                        <a href="mailto:<?php echo types_render_field("mail", array("raw" => true)) ?>">
                                                            <?php echo types_render_field("mail", array("raw" => true)) ?>
                                                        </a>

                                                    </p>

                                                    <p>

                                                        <span>Adresse:</span>

                                                        <?php echo types_render_field("adresse", array("raw" => true)) ?>

                                                        <?php echo types_render_field("code-postale", array("raw" => true)) ?>

                                                        <?php echo types_render_field("ville", array("raw" => true)) ?>

                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </li>
                                </a>
                                <?php

                            }

                            ?>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

        <div id="map-cols" class="col-md-4 hidden-sm hidden-xs">

            <button  id="close-map" class="pull-right btn btn-default close"><i class="material-icons pull-right">clear</i></button>

            <div id="map" class="">

            </div>

        </div>

    </div>

</section>

<script src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyBPSNe-58QyVSPI0SOnCLXQLFnLjNt9Usg"></script>
<script src="<?php echo get_template_directory_uri() ?>/library/js/markerclusterer.js"></script>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

        var latlng = new google.maps.LatLng(45.0854424, 3.5063323);

        var geocoder = new google.maps.Geocoder();

        var zoom = 9;

        var map = new google.maps.Map(document.getElementById('map'), {
            scrollwheel: false,
            zoom: zoom,
            center: latlng,
            disableDefaultUI: false,
            mapTypeId : 'roadmap'
        });
        
        var markerOptions = {
            imagePath: "<?php echo get_template_directory_uri() ?>/images/m"
        };
        var markerCluster = new MarkerClusterer(map, [], markerOptions);

<?php

$loop = new WP_Query($args);

if ($loop->have_posts()) {
    
    while ($loop->have_posts()) : $loop->the_post();

        if (types_render_field("latitude", array("raw" => true)) !== "" && types_render_field("longitude", array("raw" => true)) !== "") {

            ?>
                    var myLatlng = new google.maps.LatLng(parseFloat(<?php echo types_render_field("latitude", array("raw" => true)) ?>), parseFloat(<?php echo types_render_field("longitude", array("raw" => true)) ?>));
                    var infowindow = new google.maps.InfoWindow({
                        content: "<div class='infowindow'><p><strong><?php echo get_the_title(); ?></strong></p><p><?php echo types_render_field("adresse", array("raw" => true)) . ", " . types_render_field("code-postal", array("raw" => true)) . " " . types_render_field("ville", array("raw" => true)) ?></p><p><a href='<?php echo the_permalink(); ?>?rubrique=<?php echo $rubrique->term_id ?>'>Voir la fiche entreprise</a></p></div>"
                    });                
                    getMarker(myLatlng, infowindow);

            <?php

        } else {

            ?>
                    geocoder.geocode({'address': "<?php echo types_render_field("adresse", array("raw" => true)) . ", " . types_render_field("code-postal", array("raw" => true)) . " " . types_render_field("ville", array("raw" => true)) ?>"},

                        function (results, status) {

                            if (status === google.maps.GeocoderStatus.OK) {

                                var latitude = results[0].geometry.location.lat();
                                var longitude = results[0].geometry.location.lng();
                                var myLatlng = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
                                var infowindow = new google.maps.InfoWindow({
                                    content: "<div class='infowindow'><p><strong><?php echo get_the_title(); ?></strong></p><p><?php echo types_render_field("adresse", array("raw" => true)) . ", " . types_render_field("code-postal", array("raw" => true)) . " " . types_render_field("ville", array("raw" => true)) ?></p><p><a href='<?php echo the_permalink(); ?>?rubrique=<?php echo $rubrique->term_id ?>'>Voir la fiche entreprise</a></p></div>"
                                });
                                getMarker(myLatlng, infowindow);

                            } else {

                                console.log("<?php echo get_the_title(); ?> not found");
                            }

                        });
                            
            <?php

            }

            ?>
        
        <?php
        
        endwhile;

        ?>
    
        function getMarker(myLatlng, infowindow){
            
            var marker = new google.maps.Marker({
                    position: myLatlng,
                    icon: '/wp-content/themes/wp-bootstrap/images/puce-map.png',
                    map: map
                });

            google.maps.event.addListener(marker, 'click', makeMapListener(infowindow, map, marker));
            
            markerCluster.addMarker(marker, true);
            
            

        }
    
        map.setZoom(zoom);

    
<?php

} // endif

?>


        var prevOpen = null;

        function makeMapListener(window, map, markers) {

            return function () {

                if (prevOpen !== null) {

                    prevOpen.close();

                }

                prevOpen = window;

                window.open(map, markers);

            };

        }

        var element = document.querySelectorAll('.slimScroll');





        // Apply slim scroll plugin

        var one = new slimScroll(element[0], {

            'wrapperClass': 'scroll-wrapper unselectable mac',

            'scrollBarContainerClass': 'scrollBarContainer',

            'scrollBarContainerSpecialClass': 'animate',

            'scrollBarClass': 'scroll',

            'keepFocus': true

        });

        $('.result-line').on('show.bs.collapse', function (e) {

            $(this).find(".line").addClass("active");

 

        });

        $('.result-line').on('hidden.bs.collapse', function (e) {

            $(this).find(".line").removeClass("active");

        });
        var tpl_url =  "<?php echo get_template_directory_uri();?>" ;
        $.ajax({
            type: "GET",
            url: tpl_url+"/commune.csv",
            dataType: "text",
            maxShowItems:5,
            success: function(data) {processData(data);}
        });
        var a = [];
        function processData(myTxt) {
            var myLines = myTxt.split(/\r\n|\n/);
            for (var i=1; i<myLines.length; i++) {
                a.push(myLines[i]);
            }
//            console.log(a);
            $( "#localisation" ).autocomplete({                
                source: a,
                open: function(event,ui){
                    $(this).data("uiAutocomplete").menu.element.children().slice(5).remove();
                   }
            });
        }
        
    });

</script>

<?php 

get_footer();




