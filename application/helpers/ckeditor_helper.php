<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ckeditor
* Использование
*   echo form_ckeditor(array(
*       'id'              ='textarea_id',
*       'width'           => '500',
*       'height'          => '300',
*       'value'         => htmlentities($textarea_value)
*   ));
*/
function form_ckeditor($data)
{
    $data['language'] = isset($data['language']) ? $data['language'] : 'ru';
    
    $size    = isset($data['width']) ? 'width: "'.$data['width'].'", ' : '';
    $size  .= isset($data['height']) ? 'height: "'.$data['height'].'", ' : '';
    
    $options = '{'.
            $size.
            'language: "'.$data['language'].'", 
            
            stylesCombo_stylesSet: "my_styles",
            
            startupOutlineBlocks: true,
            entities: false,
            entities_latin: false,
            entities_greek: false,
            forcePasteAsPlainText: false,
            
            filebrowserImageUploadUrl : "/basic_functions/ckupload", // << My own file uploader
            filebrowserImageBrowseUrl : "/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images", // << My own file browser
            filebrowserImageWindowWidth : "80%",
            filebrowserImageWindowHeight : "80%",   

            
            toolbar :[
                ["Source","-","FitWindow","ShowBlocks","-","Preview"],
                ["Undo","Redo","-","Find","Replace","-","SelectAll","RemoveFormat"],
                ["Cut","Copy","Paste","PasteText","PasteWord","-","Print","SpellCheck"],
                ["Form","Checkbox","Radio","TextField","Textarea","Select","Button","ImageButton","HiddenField"],
                ["About"],
                ["NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-", "BidiLtr", "BidiRtl", "Language" ],
                    
                "/",
                
                ["Bold","Italic","Underline"],
                ["OrderedList","UnorderedList","-","Blockquote","CreateDiv"],
                
                ["Image","Table"],
                
                ["Link","Unlink","Anchor"],
                ["Rule","SpecialChar"],
                ["Styles"],
                ["Iframe"]
            ]
        }';
    
    
    $my_styles = 'CKEDITOR.addStylesSet("my_styles",
        [
            // Block Styles
            { name : "H3", element : "h3"},
            { name : "Heading 4", element : "h4"},
            { name : "Heading 5", element : "h5"},
            { name : "Heading 6", element : "h6"},
            { name : "Document Block", element : "div"},
            { name : "Preformatted Text", element : "pre"},
            { name : "Address", element : "address"},
        
            // Inline Styles
            { name: "Centered paragraph", element: "p", attributes: { "class": "center" } },
            
            { name: "IMG bordered", element: "img", attributes: { "class": "bordered" } },
            
            { name: "IMG left", element: "img", attributes: { "class": "left" } },
            { name: "IMG right", element: "img", attributes: { "class": "right" } },
            
            { name: "IMG left bordered", element: "img", attributes: { "class": "left bordered" } },
            { name: "IMGright bordered", element: "img", attributes: { "class": "right bordered" } },
        ]);';
    

    return 
    // fix: move to <HEAD...
    '<script type="text/javascript" src="'.base_url().'ckeditor/ckeditor.js"></script>' .

    // put the CKEditor
     '<script type="text/javascript">' .
           $my_styles .
            'CKEDITOR.replace("'.$data['id'].'", ' . $options . ');</script>';
} 

function form_ckeditor_text($data)
{
    $data['language'] = isset($data['language']) ? $data['language'] : 'ru';
    
    $size    = isset($data['width']) ? 'width: "'.$data['width'].'", ' : '';
    $size  .= isset($data['height']) ? 'height: "'.$data['height'].'", ' : '';
    
    $options = '{'.
            $size.
            'language: "'.$data['language'].'", 
            
            stylesCombo_stylesSet: "my_styles",
            
            startupOutlineBlocks: true,
            entities: false,
            entities_latin: false,
            entities_greek: false,
            forcePasteAsPlainText: false,
            
            filebrowserImageUploadUrl : "/basic_functions/ckupload", // << My own file uploader
            filebrowserImageBrowseUrl : "/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images", // << My own file browser
            filebrowserImageWindowWidth : "80%",
            filebrowserImageWindowHeight : "80%",   

            
            toolbar :[
                ["Source","-","FitWindow","ShowBlocks","-","Preview"],
                ["Undo","Redo","-","Find","Replace","-","SelectAll","RemoveFormat"],
                ["Cut","Copy","Paste","PasteText","PasteWord","-","Print","SpellCheck"],
                ["Form","Checkbox","Radio","TextField","Textarea","Select","Button","ImageButton","HiddenField"],
                ["About"],
                ["NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-", "BidiLtr", "BidiRtl", "Language" ],
                    
                "/",
                
                ["Bold","Italic","Underline"],
                ["OrderedList","UnorderedList","-","Blockquote","CreateDiv"],
                
                ["Image","Table"],
                
                ["Link","Unlink","Anchor"],
                ["Rule","SpecialChar"],
                ["Styles"]
            ]
        }';
    
    
    /*
    $my_styles = 'CKEDITOR.addStylesSet("my_styles",
        [
            // Block Styles
            { name : "H3", element : "h3"},
            { name : "Heading 4", element : "h4"},
            { name : "Heading 5", element : "h5"},
            { name : "Heading 6", element : "h6"},
            { name : "Document Block", element : "div"},
            { name : "Preformatted Text", element : "pre"},
            { name : "Address", element : "address"},
        
            // Inline Styles
            { name: "Centered paragraph", element: "p", attributes: { "class": "center" } },
            
            { name: "IMG bordered", element: "img", attributes: { "class": "bordered" } },
            
            { name: "IMG left", element: "img", attributes: { "class": "left" } },
            { name: "IMG right", element: "img", attributes: { "class": "right" } },
            
            { name: "IMG left bordered", element: "img", attributes: { "class": "left bordered" } },
            { name: "IMGright bordered", element: "img", attributes: { "class": "right bordered" } },
        ]);';*/
    

    return '<script type="text/javascript">CKEDITOR.replace("'.$data['id'].'", ' . $options . ');</script>';
}

function form_ckeditor_text2($data)
{
    $data['language'] = isset($data['language']) ? $data['language'] : 'ru';
    
    $size    = isset($data['width']) ? 'width: "'.$data['width'].'", ' : '';
    $size  .= isset($data['height']) ? 'height: "'.$data['height'].'", ' : '';
    
    $options = '{'.
            $size.
            'language: "'.$data['language'].'", 
            
            stylesCombo_stylesSet: "my_styles",
            
            startupOutlineBlocks: true,
            entities: false,
            entities_latin: false,
            entities_greek: false,
            forcePasteAsPlainText: false,
            
            filebrowserImageUploadUrl : "/basic_functions/ckupload", // << My own file uploader
            filebrowserImageBrowseUrl : "/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images", // << My own file browser
            filebrowserImageWindowWidth : "80%",
            filebrowserImageWindowHeight : "80%",   

            
            toolbar :[
                ["Source","-","FitWindow","ShowBlocks","-","Preview"],
                ["Undo","Redo","-","Find","Replace","-","SelectAll","RemoveFormat"],
                ["Cut","Copy","Paste","PasteText","PasteWord","-","Print","SpellCheck"],
                ["Form","Checkbox","Radio","TextField","Textarea","Select","Button","ImageButton","HiddenField"],
                ["About"],
                ["NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-", "BidiLtr", "BidiRtl", "Language" ],
                    
                "/",
                
                ["Bold","Italic","Underline"],
                ["OrderedList","UnorderedList","-","Blockquote","CreateDiv"],
                
                ["Image","Table"],
                
                ["Link","Unlink","Anchor"],
                ["Rule","SpecialChar"],
                ["Styles"]
            ]
        }';
    
    
    $my_styles = 'CKEDITOR.addStylesSet("my_styles",
        [
            // Block Styles
            { name : "H3", element : "h3"},
            { name : "Heading 4", element : "h4"},
            { name : "Heading 5", element : "h5"},
            { name : "Heading 6", element : "h6"},
            { name : "Document Block", element : "div"},
            { name : "Preformatted Text", element : "pre"},
            { name : "Address", element : "address"},
        
            // Inline Styles
            { name: "Centered paragraph", element: "p", attributes: { "class": "center" } },
            
            { name: "IMG bordered", element: "img", attributes: { "class": "bordered" } },
            
            { name: "IMG left", element: "img", attributes: { "class": "left" } },
            { name: "IMG right", element: "img", attributes: { "class": "right" } },
            
            { name: "IMG left bordered", element: "img", attributes: { "class": "left bordered" } },
            { name: "IMGright bordered", element: "img", attributes: { "class": "right bordered" } },
        ]);';
    

    return '<script type="text/javascript">CKEDITOR.replace("'.$data['id'].'", ' . $options . ');</script>';
}