<?php
class MpxMimes extends Mpx{

    protected $_mimes;

    public function __construct(array $config = array()) {
        $this->_setupMimes();
    }

    /**
    * @param string  $ext       file extension
    * @return bool true or false
    */
    public function validExtension($ext = null) {
        if(array_key_exists($ext, $this->_mimes)) {
            return true;
        }
        throw new Exception('Invalid file extension.');
    }

    /**
    * @param string  $ext       file extension
    * @param string  $mimeType  file mimeType
    * @return bool true or false
    */
    public function getTitle($ext = null, $mimeType = null) {
        if($this->validExtension($ext)) { // If the extension exists
            if( in_array($mimeType, $this->_mimes[$ext]['mimeTypes']) ) {
                return $this->_mimes[$ext]['title'];
            }
            throw new Exception('Mime type does not match extension.');
        }
    }

    

    private function _setupMimes() {
        /* MPX File Format Mime Types For Uploading
        * extension => array(file format, mimeTypes allowed to use file format)
        */
        $this->_mimes = array(
        	'unk' => array(
        		'title' => 'Unknown',
        		'mimeTypes' => array('application/unknown')
        	),
        	'3gpp' => array(
        		'title' => '3GPP',
        		'mimeTypes' => array('audio/3gpp')
        	),
        	'3gp' => array(
        		'title' => '3GPP',
        		'mimeTypes' => array('audio/3gpp')
        	),
        	'3gpp2' => array(
        		'title' => '3GPP2',
        		'mimeTypes' => array('audio/3gpp2')
        	),
        	'3gp2' => array(
        		'title' => '3GPP2',
        		'mimeTypes' => array('audio/3gpp2')
        	),
        	'3g2' => array(
        		'title' => '3GPP2',
        		'mimeTypes' => array('audio/3gpp2')
        	),
        	'aac' => array(
        		'title' => 'AAC',
        		'mimeTypes' => array('audio/mp4')
        	),
        	'acp' => array(
        		'title' => 'AAC',
        		'mimeTypes' => array('audio/mp4')
        	),
        	'm4a' => array(
        		'title' => 'AAC',
        		'mimeTypes' => array('audio/mp4')
        	),
        	'adf' => array(
        		'title' => 'ADF',
        		'mimeTypes' => array('application/adf+xml')
        	),
        	'asx' => array(
        		'title' => 'ASX',
        		'mimeTypes' => array('video/x-ms-wvx')
        	),
        	'wax' => array(
        		'title' => 'ASX',
        		'mimeTypes' => array('video/x-ms-wvx')
        	),
        	'wvx' => array(
        		'title' => 'ASX',
        		'mimeTypes' => array('video/x-ms-wvx')
        	),
        	'avi' => array(
        		'title' => 'AVI',
        		'mimeTypes' => array('video/avi')
        	),
        	'bif' => array(
        		'title' => 'BIF',
        		'mimeTypes' => array('application/x-roku-bif')
        	),
        	'bmp' => array(
        		'title' => 'BMP',
        		'mimeTypes' => array('image/bmp')
        	),
        	'c2idx' => array(
        		'title' => 'C2',
        		'mimeTypes' => array('application/c2+octet-stream')
        	),
        	'css' => array(
        		'title' => 'CSS',
        		'mimeTypes' => array('text/css')
        	),
        	'dfxp' => array(
        		'title' => 'DFXP',
        		'mimeTypes' => array('application/ttaf+xml')
        	),
        	'dfx' => array(
        		'title' => 'DFXP',
        		'mimeTypes' => array('application/ttaf+xml')
        	),
        	'dfp' => array(
        		'title' => 'DFXP',
        		'mimeTypes' => array('application/ttaf+xml')
        	),
        	'dv' => array(
        		'title' => 'DV',
        		'mimeTypes' => array('video/x-dv')
        	),
        	'emf' => array(
        		'title' => 'EMF',
        		'mimeTypes' => array(
        						'application/emf',
        						'application/x-emf',
        						'image/x-emf',
        						'image/x-mgxemf',
        						'image/x-xbitmap')
        	),
        	'eps' => array(
        		'title' => 'EPS',
        		'mimeTypes' => array(
        						'application/postscript',
        						'application/eps',
        						'application/x-eps',
        						'image/eps',
        						'image/x-eps')
        	),
        	'epi' => array(
        		'title' => 'EPS',
        		'mimeTypes' => array(
        						'application/postscript',
        						'application/eps',
        						'application/x-eps',
        						'image/eps',
        						'image/x-eps')
        	),
        	'epsf' => array(
        		'title' => 'EPS',
        		'mimeTypes' => array(
        						'application/postscript',
        						'application/eps',
        						'application/x-eps',
        						'image/eps',
        						'image/x-eps')
        	),
        	'epsi' => array(
        		'title' => 'EPS',
        		'mimeTypes' => array(
        						'application/postscript',
        						'application/eps',
        						'application/x-eps',
        						'image/eps',
        						'image/x-eps')
        	),
        	'csv' => array(
        		'title' => 'Excel',
        		'mimeTypes' => array('application/vnd.ms-excel')
        	),
        	'dif' => array(
        		'title' => 'Excel',
        		'mimeTypes' => array('application/vnd.ms-excel')
        	),
        	'xls' => array(
        		'title' => 'Excel',
        		'mimeTypes' => array('application/vnd.ms-excel')
        	),
        	'xlsm' => array(
        		'title' => 'Excel',
        		'mimeTypes' => array('application/vnd.ms-excel')
        	),
        	'xlt' => array(
        		'title' => 'Excel',
        		'mimeTypes' => array('application/vnd.ms-excel')
        	),
        	'xlsx' => array(
        		'title' => 'Excel',
        		'mimeTypes' => array('application/vnd.ms-excel')
        	),
        	'xltx' => array(
        		'title' => 'Excel',
        		'mimeTypes' => array('application/vnd.ms-excel')
        	),
        	'bat' => array(
        		'title' => 'EXE',
        		'mimeTypes' => array('application/octet-stream')
        	),
        	'cmd' => array(
        		'title' => 'EXE',
        		'mimeTypes' => array('application/octet-stream')
        	),
        	'com' => array(
        		'title' => 'EXE',
        		'mimeTypes' => array('application/octet-stream')
        	),
        	'exe' => array(
        		'title' => 'EXE',
        		'mimeTypes' => array('application/octet-stream')
        	),
        	'f4m' => array(
        		'title' => 'F4M',
        		'mimeTypes' => array('application/f4m+xml')
        	),
        	'fs' => array(
        		'title' => 'Filmstrip',
        		'mimeTypes' => array('application/filmstrip+json')
        	),
        	'film' => array(
        		'title' => 'Filmstrip',
        		'mimeTypes' => array('application/filmstrip+json')
        	),
        	'rf' => array(
        		'title' => 'Flash',
        		'mimeTypes' => array('application/x-shockwave-flash')
        	),
        	'swf' => array(
        		'title' => 'Flash',
        		'mimeTypes' => array('application/x-shockwave-flash')
        	),
        	'flv' => array(
        		'title' => 'FLV',
        		'mimeTypes' => array('video/x-flv')
        	),
        	'f4v' => array(
        		'title' => 'FLV',
        		'mimeTypes' => array('video/x-flv')
        	),
        	'f4p' => array(
        		'title' => 'FLV',
        		'mimeTypes' => array('video/x-flv')
        	),
        	'f4a' => array(
        		'title' => 'FLV',
        		'mimeTypes' => array('video/x-flv')
        	),
        	'f4b' => array(
        		'title' => 'FLV',
        		'mimeTypes' => array('video/x-flv')
        	),
        	'flx' => array(
        		'title' => 'FLX',
        		'mimeTypes' => array('text/x-flx')
        	),
        	'gif' => array(
        		'title' => 'GIF',
        		'mimeTypes' => array('image/gif')
        	),
        	'htm' => array(
        		'title' => 'HTML',
        		'mimeTypes' => array('text/html')
        	),
        	'html' => array(
        		'title' => 'HTML',
        		'mimeTypes' => array('text/html')
        	),
        	'ico' => array(
        		'title' => 'Icon',
        		'mimeTypes' => array(
        						'image/ico',
        						'image/x-icon',
        						'application/ico',
        						'application/x-ico',
        						'application/x-win-bitmap',
        						'image/vnd.microsoft.ico')
        	),
        	'icon' => array(
        		'title' => 'Icon',
        		'mimeTypes' => array(
        						'image/ico',
        						'image/x-icon',
        						'application/ico',
        						'application/x-ico',
        						'application/x-win-bitmap',
        						'image/vnd.microsoft.ico')
        	),
        	'ism' => array(
        		'title' => 'ISM',
        		'mimeTypes' => array('application/ism+xml')
        	),
        	'isml' => array(
        		'title' => 'ISM',
        		'mimeTypes' => array('application/ism+xml')
        	),
        	'jpe' => array(
        		'title' => 'JPEG',
        		'mimeTypes' => array('image/jpeg')
        	),
        	'jpeg' => array(
        		'title' => 'JPEG',
        		'mimeTypes' => array('image/jpeg')
        	),
        	'jpg' => array(
        		'title' => 'JPEG',
        		'mimeTypes' => array('image/jpeg')
        	),
        	'lxf' => array(
        		'title' => 'LXF',
        		'mimeTypes' => array('video/x-lxf')
        	),
        	'm3u' => array(
        		'title' => 'M3U',
        		'mimeTypes' => array(
        						'audio/x-mpegurl',
        						'vnd.apple.mpegURL',
        						'application/x-mpegURL')
        	),
        	'm3u8' => array(
        		'title' => 'M3U',
        		'mimeTypes' => array(
        						'audio/x-mpegurl',
        						'vnd.apple.mpegURL',
        						'application/x-mpegURL')
        	),
        	'mkv' => array(
        		'title' => 'Matroska',
        		'mimeTypes' => array(
        						'video/x-matroska',
        						'audio/x-matroska')
        	),
        	'mk3d' => array(
        		'title' => 'Matroska',
        		'mimeTypes' => array(
        						'video/x-matroska',
        						'audio/x-matroska')
        	),
        	'mka' => array(
        		'title' => 'Matroska',
        		'mimeTypes' => array(
        						'video/x-matroska',
        						'audio/x-matroska')
        	),
        	'mks' => array(
        		'title' => 'Matroska',
        		'mimeTypes' => array(
        						'video/x-matroska',
        						'audio/x-matroska')
        	),
        	'qmx' => array(
        		'title' => 'Move',
        		'mimeTypes' => array('video/x-qmx')
        	),
        	'qvt' => array(
        		'title' => 'Move',
        		'mimeTypes' => array('video/x-qmx')
        	),
        	'mp3' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('audio/mpeg')
        	),
        	'm1v' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mp1' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mp2' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mp2v' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mpga' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mpa' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mpe' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mpeg' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mpg' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mpv' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mpv2' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'm2p' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'ts' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'bbts' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'm1a' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'm2a' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'm2v' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'm2t' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'm2ts' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mts' => array(
        		'title' => 'MP3',
        		'mimeTypes' => array('video/mpeg')
        	),
        	'mpd' => array(
        		'title' => 'MPEG-DASH',
        		'mimeTypes' => array(
        						'application/dash+xml',
        						'video/vnd.mpeg.dash.mpd')
        	),
        	'mp4' => array(
        		'title' => 'MPEG4',
        		'mimeTypes' => array('video/mp4')
        	),
        	'm4p' => array(
        		'title' => 'MPEG4',
        		'mimeTypes' => array('video/mp4')
        	),
        	'm4v' => array(
        		'title' => 'MPEG4',
        		'mimeTypes' => array('video/mp4')
        	),
        	'msi' => array(
        		'title' => 'MSI',
        		'mimeTypes' => array('application/msi')
        	),
        	'mxf' => array(
        		'title' => 'MXF',
        		'mimeTypes' => array('video/mxf')
        	),
        	'ogv' => array(
        		'title' => 'Ogg',
        		'mimeTypes' => array(
        						'video/ogg',
        						'audio/ogg',
        						'application/ogg')
        	),
        	'oga' => array(
        		'title' => 'Ogg',
        		'mimeTypes' => array(
        						'video/ogg',
        						'audio/ogg',
        						'application/ogg')
        	),
        	'ogx' => array(
        		'title' => 'Ogg',
        		'mimeTypes' => array(
        						'video/ogg',
        						'audio/ogg',
        						'application/ogg')
        	),
        	'ogg' => array(
        		'title' => 'Ogg',
        		'mimeTypes' => array(
        						'video/ogg',
        						'audio/ogg',
        						'application/ogg')
        	),
        	'spx' => array(
        		'title' => 'Ogg',
        		'mimeTypes' => array(
        						'video/ogg',
        						'audio/ogg',
        						'application/ogg')
        	),
        	'pdf' => array(
        		'title' => 'PDF',
        		'mimeTypes' => array('application/pdf')
        	),
        	'pls' => array(
        		'title' => 'PLS',
        		'mimeTypes' => array('audio/scpls')
        	),
        	'png' => array(
        		'title' => 'PNG',
        		'mimeTypes' => array('image/png')
        	),
        	'pps' => array(
        		'title' => 'PPT',
        		'mimeTypes' => array('application/vnd.ms-powerpoint')
        	),
        	'ppt' => array(
        		'title' => 'PPT',
        		'mimeTypes' => array('application/vnd.ms-powerpoint')
        	),
        	'pptx' => array(
        		'title' => 'PPT',
        		'mimeTypes' => array('application/vnd.ms-powerpoint')
        	),
        	'ppsx' => array(
        		'title' => 'PPT',
        		'mimeTypes' => array('application/vnd.ms-powerpoint')
        	),
        	'pot' => array(
        		'title' => 'PPT',
        		'mimeTypes' => array('application/vnd.ms-powerpoint')
        	),
        	'potx' => array(
        		'title' => 'PPT',
        		'mimeTypes' => array('application/vnd.ms-powerpoint')
        	),
        	'mov' => array(
        		'title' => 'QT',
        		'mimeTypes' => array('video/quicktime')
        	),
        	'qt' => array(
        		'title' => 'QT',
        		'mimeTypes' => array('video/quicktime')
        	),
        	'ram' => array(
        		'title' => 'RAM',
        		'mimeTypes' => array('audio/x-pn-realaudio')
        	),
        	'ra' => array(
        		'title' => 'Real',
        		'mimeTypes' => array('video/vnd.rn-realvideo')
        	),
        	'rm' => array(
        		'title' => 'Real',
        		'mimeTypes' => array('video/vnd.rn-realvideo')
        	),
        	'rv' => array(
        		'title' => 'Real',
        		'mimeTypes' => array('video/vnd.rn-realvideo')
        	),
        	'rgb' => array(
        		'title' => 'RGB',
        		'mimeTypes' => array('application/rgb+xml')
        	),
        	'sami' => array(
        		'title' => 'SAMI',
        		'mimeTypes' => array('text/x-ms-sami')
        	),
        	'sbv' => array(
        		'title' => 'SBV',
        		'mimeTypes' => array('text/sbv')
        	),
        	'scc' => array(
        		'title' => 'SCC',
        		'mimeTypes' => array('text/scc')
        	),
        	'js' => array(
        		'title' => 'Script',
        		'mimeTypes' => array('text/javascript')
        	),
        	'vbs' => array(
        		'title' => 'Script',
        		'mimeTypes' => array('text/javascript')
        	),
        	'smi' => array(
        		'title' => 'SMIL',
        		'mimeTypes' => array('application/smil+xml')
        	),
        	'smil' => array(
        		'title' => 'SMIL',
        		'mimeTypes' => array('application/smil+xml')
        	),
        	'tt' => array(
        		'title' => 'SMPTE-TT',
        		'mimeTypes' => array('application/smptett+xml')
        	),
        	'smptett' => array(
        		'title' => 'SMPTE-TT',
        		'mimeTypes' => array('application/smptett+xml')
        	),
        	'ttml' => array(
        		'title' => 'SMPTE-TT',
        		'mimeTypes' => array('application/smptett+xml')
        	),
        	'srt' => array(
        		'title' => 'SRT',
        		'mimeTypes' => array('text/srt')
        	),
        	'sub' => array(
        		'title' => 'SUB',
        		'mimeTypes' => array('text/sub')
        	),
        	'tcm' => array(
        		'title' => 'TCM',
        		'mimeTypes' => array('application/tcm+xml')
        	),
        	'txt' => array(
        		'title' => 'Text',
        		'mimeTypes' => array('text/plain')
        	),
        	'tif' => array(
        		'title' => 'TIFF',
        		'mimeTypes' => array('image/tiff')
        	),
        	'tiff' => array(
        		'title' => 'TIFF',
        		'mimeTypes' => array('image/tiff')
        	),
        	'vast' => array(
        		'title' => 'VAST',
        		'mimeTypes' => array('application/vast+xml')
        	),
        	'wav' => array(
        		'title' => 'WAV',
        		'mimeTypes' => array('audio/wav')
        	),
        	'webm' => array(
        		'title' => 'WebM',
        		'mimeTypes' => array(
        						'video/x-webm',
        						'video/webm',
        						'audio/webm')
        	),
        	'webvtt' => array(
        		'title' => 'WebVTT',
        		'mimeTypes' => array('text/vtt')
        	),
        	'vtt' => array(
        		'title' => 'WebVTT',
        		'mimeTypes' => array('text/vtt')
        	),
        	'wvm' => array(
        		'title' => 'Widevine',
        		'mimeTypes' => array('video/x-widevine')
        	),
        	'asf' => array(
        		'title' => 'WM',
        		'mimeTypes' => array('video/x-ms-asf')
        	),
        	'wm' => array(
        		'title' => 'WM',
        		'mimeTypes' => array('video/x-ms-asf')
        	),
        	'wma' => array(
        		'title' => 'WM',
        		'mimeTypes' => array('video/x-ms-asf')
        	),
        	'wmv' => array(
        		'title' => 'WM',
        		'mimeTypes' => array('video/x-ms-asf')
        	),
        	'nsc' => array(
        		'title' => 'WM',
        		'mimeTypes' => array('video/x-ms-asf')
        	),
        	'doc' => array(
        		'title' => 'Word',
        		'mimeTypes' => array('application/msword')
        	),
        	'dot' => array(
        		'title' => 'Word',
        		'mimeTypes' => array('application/msword')
        	),
        	'docx' => array(
        		'title' => 'Word',
        		'mimeTypes' => array('application/msword')
        	),
        	'dotx' => array(
        		'title' => 'Word',
        		'mimeTypes' => array('application/msword')
        	),
        	'xml' => array(
        		'title' => 'XML',
        		'mimeTypes' => array('text/xml')
        	),
        	'zip' => array(
        		'title' => 'Zip',
        		'mimeTypes' => array('application/x-zip-compressed')
        	),
        );
    }
}