<?php
class PadmaGoogleFonts extends PadmaWebFontProvider {


	public $id 					= 'google';
	public $name 				= 'Google Web Fonts';
	public $webfont_provider 	= 'google';
	public $load_with_ajax 		= true;


	public $sorting_options = array(
		'popularity' 	=> 'Popularity',
		'trending' 		=> 'Trending',
		'alpha' 		=> 'Alphabetically',
		'date' 			=> 'Date Added',
		'style' 		=> 'Style'
	);

	protected $api_url = PADMA_API_URL . 'googlefonts/index.php';

	// ToDo: arrange backuplocation
    protected $backup_api_url = PADMA_API_URL . 'googlefonts/index.php';


public function query_fonts($sortby = 'date', $retry = false) {
    $url = $this->api_url . '?sort=' . $sortby;
    $fonts_query = wp_remote_get($url);

    if (is_wp_error($fonts_query)) {
        error_log("Fehler bei Sortierung $sortby: " . $fonts_query->get_error_message());
        return [];
    }

    $data = wp_remote_retrieve_body($fonts_query);
    error_log("Response für Sortierung $sortby: " . $data);

    $json = json_decode($data, true);

    if (!is_array($json)) {
        error_log("JSON decode Fehler bei Sortierung $sortby");
        return [];
    }

    if (empty($json['items']) || !is_array($json['items'])) {
        error_log("Keine items für Sortierung $sortby gefunden");
        return [];
    }

    $fonts = [];

    foreach ($json['items'] as $font) {
        // Prüfe, ob alle Felder da sind, sonst skip
        if (empty($font['family']) || empty($font['variants'])) continue;

        $fonts[] = [
            'id'       => $font['family'],
            'name'     => $font['family'],
            'stack'    => '"' . $font['family'] . '", sans-serif',
            'variants' => $font['variants'],
        ];
    }

    return $fonts;
}
}
