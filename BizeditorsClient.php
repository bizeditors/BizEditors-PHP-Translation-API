<?php
class BizeditorsClient {

	private $url = 'http://www.bizeditors.com/api';
	private $api_key = '';
	private $private_key = '';

	function __construct ($api_key, $private_key) {
		$this->api_key = $api_key;
		$this->private_key = $private_key;
	}

	/**
	 * Send a plain text job
	 *
	 * @param string $text
	 *        	The text that you want to send to the server
	 * @param string $instructions
	 *        	Any additional instructions you want to set
	 * @param boolean $oneDay
	 *        	One day editing yes/no
	 */
	function sendPlainText($service, $langID, $category, $text, $instructions = FALSE, $notify_url = FALSE) {
		if (trim ( $text ) == '') {
			return;
		}
		$job = array (
                'service' => $service,
                'langID' => $langID,
                'category' => $category,
                'body' => $text,
                'instructions' => $instructions,
                'notify_url' => $notify_url
		);
		$data ['job'] = $job;
		$params = $this->setParams ( $this->api_key, $this->private_key, $data );
		$reply = $this->doPost ( $this->url . '/jobs', $params );
		return json_decode($reply);
	}

	/**
	 * Send a document for editing, MUST be a docx, xlsx or pptx
	 *
	 * @param string $filename
	 * @param string $instructions
	 * @param boolean $oneDay
	 */
	function sendDocument($service, $langID, $category, $filename, $instructions = FALSE, $notify_url = FALSE) {
		$doc = fread ( fopen ( $filename, "r" ), filesize ( $filename ) );
		$job = array (
                'service' => $service,
                'langID' => $langID,
                'category' => $category,
				'instructions' => $instructions,
				'document' => base64_encode ( $doc ),
                'filename' => $filename,
                'notify_url' => $notify_url
		);

		$data ['job'] = $job;
		$params = $this->setParams ( $this->api_key, $this->private_key, $data );
		$reply = $this->doPost ( $this->url . '/jobs', $params );
		return json_decode ( $reply );
	}

    /**
     * Get the no. of words in a document
     */
    function getWordCountDocument($service, $langID, $filename) {
        $doc = fread ( fopen ( $filename, "r" ), filesize ( $filename ) );
        $job = array (
            'service' => $service,
            'langID' => $langID,
            'document' => base64_encode ( $doc ),
            'filename' => $filename
        );

        $data ['job'] = $job;
        $params = $this->setParams ( $this->api_key, $this->private_key, $data );
        $reply = $this->doPost ( $this->url . '/wordcount', $params );
        return json_decode ( $reply );
    }

    /**
     * Get the no. of words in a text
     */
    function getWordCountPlainText($service, $langID, $text) {
        if (trim ( $text ) == '') {
            return;
        }
        $job = array (
            'service' => $service,
            'langID' => $langID,
            'body' => $text
        );
        $data ['job'] = $job;
        $params = $this->setParams ( $this->api_key, $this->private_key, $data );
        $reply = $this->doPost ( $this->url . '/wordcount', $params );
        return json_decode($reply);
    }

	/**
	 * Fetch the current word balance from the server
	 */
	function getWordBalance() {
		$data = null;
		$params = $this->setParams ( $this->api_key, $this->private_key, $data );
		$reply = $this->doPost ( $this->url . '/balance', $params );
		return json_decode ( $reply );
	}

	/**
	 * Post a comment to an existing job
	 *
	 * @param string $jobId
	 *        	unique id of the job
	 * @param string $comment
	 *        	the comment you want to add
	 */
	function postComment($jobId, $comment) {
        /*
		if (empty ( $comment ) || empty ( $jobId )) {
			return;
		}
        */
		$data ['comment'] = $comment;
		$params = $this->setParams ( $this->api_key, $this->private_key, $data );
		$reply = $this->doPost ( $this->url . '/jobs/' . $jobId . '/comment', $params );
		return json_decode ( $reply );
	}

	/**
	 * Fetch the status of a job from the server
	 *
	 * @param string $jobId
	 *        	The job id you want to fetch the status of
	 */
	function getStatus($jobId, $format) {
		if (empty ( $jobId )) {
			return;
		}
        $data ['format'] = $format;
		$params = $this->setParams ( $this->api_key, $this->private_key, $data );
		$reply = $this->doPost ( $this->url . '/jobs/' . $jobId, $params );
		return json_decode ( $reply, TRUE );
	}


	/**
	 * Helper function - set the authentification and data parameters
	 *
	 * @param string $api_key
	 *        	public API key
	 * @param string $private_key
	 *        	private API key
	 * @param array $data
	 * @return multitype:unknown NULL
	 */
	function setParams($api_key, $private_key, $data) {
		$params = array (
				'api_key' => $api_key,
				'ts' => gmdate ( 'U' ),
				'data' => json_encode ( $data )
		);

		$hmac = hash_hmac ( 'sha1', $params ['ts'], $private_key );
		$params ['api_sig'] = $hmac;
		return $params;
	}

	/**
	 * Helper function - post the request to the server
	 *
	 * @param string $url
	 * @param string $params
	 * @return string the server response
	 */
	function doPost($url, $params) {
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );
		$response = curl_exec ( $ch );
		curl_close ( $ch );
		return $response;
	}
}
?>
