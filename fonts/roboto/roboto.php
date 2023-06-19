<?php
namespace unlockedlabs\unlocked;
header("Content-Type: text/css "); 
$fontfacecss = "";

foreach(explode(',',$_GET['weight']) as $key=>$val){

    $fontfacecss.= <<<NEWFONTWEIGHT
    /* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Thin Italic'), local('Roboto-ThinItalic'), url(roboto-cyrillic-ext-100i.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Thin Italic'), local('Roboto-ThinItalic'), url(roboto-cyrillic-100i.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Thin Italic'), local('Roboto-ThinItalic'), url(roboto-latin-ext-100i.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Thin Italic'), local('Roboto-ThinItalic'), url(roboto-latin-100i.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}


/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Light Italic'), local('Roboto-LightItalic'), url(roboto-cyrillic-ext-300i.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Light Italic'), local('Roboto-LightItalic'), url(roboto-cyrillic-300i.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Light Italic'), local('Roboto-LightItalic'), url(roboto-latin-ext-300i.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Light Italic'), local('Roboto-LightItalic'), url(roboto-latin-300i.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}


/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Italic'), local('Roboto-Italic'), url(roboto-cyrillic-ext-400i.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Italic'), local('Roboto-Italic'), url(roboto-cyrillic-400i.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Italic'), local('Roboto-Italic'), url(roboto-latin-ext-400i.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Italic'), local('Roboto-Italic'), url(roboto-latin-400i.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}


/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Medium Italic'), local('Roboto-MediumItalic'), url(roboto-cyrillic-ext-500i.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Medium Italic'), local('Roboto-MediumItalic'), url(roboto-cyrillic-500i.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Medium Italic'), local('Roboto-MediumItalic'), url(roboto-latin-ext-500i.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Medium Italic'), local('Roboto-MediumItalic'), url(roboto-latin-500i.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Bold Italic'), local('Roboto-BoldItalic'), url(roboto-cyrillic-ext-700i.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Bold Italic'), local('Roboto-BoldItalic'), url(roboto-cyrillic-700i.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Bold Italic'), local('Roboto-BoldItalic'), url(roboto-latin-ext-700i.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Bold Italic'), local('Roboto-BoldItalic'), url(roboto-latin-700i.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}


/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Black Italic'), local('Roboto-BlackItalic'), url(roboto-cyrillic-ext-900i.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Black Italic'), local('Roboto-BlackItalic'), url(roboto-cyrillic-900i.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Black Italic'), local('Roboto-BlackItalic'), url(roboto-latin-ext-900i.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: italic;
  font-weight: {$val};
  src: local('Roboto Black Italic'), local('Roboto-BlackItalic'), url(roboto-latin-900i.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Thin'), local('Roboto-Thin'), url(roboto-cyrillic-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Thin'), local('Roboto-Thin'), url(roboto-cyrillic-{$val}.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Thin'), local('Roboto-Thin'), url(roboto-latin-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Thin'), local('Roboto-Thin'), url(roboto-latin-{$val}.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}


/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Light'), local('Roboto-Light'), url(roboto-cyrillic-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Light'), local('Roboto-Light'), url(roboto-cyrillic-{$val}.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Light'), local('Roboto-Light'), url(roboto-latin-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Light'), local('Roboto-Light'), url(roboto-latin-{$val}.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto'), local('Roboto-Regular'), url(roboto-cyrillic-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto'), local('Roboto-Regular'), url(roboto-cyrillic-{$val}.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto'), local('Roboto-Regular'), url(roboto-latin-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto'), local('Roboto-Regular'), url(roboto-latin-{$val}.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Medium'), local('Roboto-Medium'), url(roboto-cyrillic-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Medium'), local('Roboto-Medium'), url(roboto-cyrillic-{$val}.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Medium'), local('Roboto-Medium'), url(roboto-latin-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Medium'), local('Roboto-Medium'), url(roboto-latin-{$val}.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Bold'), local('Roboto-Bold'), url(roboto-cyrillic-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Bold'), local('Roboto-Bold'), url(roboto-cyrillic-{$val}.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Bold'), local('Roboto-Bold'), url(roboto-latin-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Bold'), local('Roboto-Bold'), url(roboto-latin-{$val}.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}


/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Black'), local('Roboto-Black'), url(roboto-cyrillic-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
}

/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Black'), local('Roboto-Black'), url(roboto-cyrillic-{$val}.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}

/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Black'), local('Roboto-Black'), url(roboto-latin-ext-{$val}.woff2) format('woff2');
  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: {$val};
  src: local('Roboto Black'), local('Roboto-Black'), url(roboto-latin-{$val}.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}









NEWFONTWEIGHT;
}
echo $fontfacecss;
?>
