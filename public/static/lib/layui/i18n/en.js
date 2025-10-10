window.LAYUI_I18N = window.LAYUI_I18N || {};

window.LAYUI_I18N['en'] = {
    "code": {
        "copy": "Copy code",
        "copied": "Copied",
        "copyError": "Copy failed",
        "maximize": "Maximize",
        "restore": "Restore",
        "preview": "Preview in new window"
    },
    "colorpicker": {
        "clear": "Clear",
        "confirm": "Confirm"
    },
    "dropdown": {
        "noData": "No data available"
    },
    "flow": {
        "loadMore": "Load more",
        "noMore": "No more data"
    },
    "form": {
        "select": {
            "noData": "No data available",
            "noMatch": "No matching data",
            "placeholder": "Please select"
        },
        "validateMessages": {
            "required": "This field is required",
            "phone": "Invalid phone number format",
            "email": "Invalid email format",
            "url": "Invalid URL format",
            "number": "Only numbers are allowed",
            "date": "Invalid date format",
            "identity": "Invalid ID card format"
        },
        "verifyErrorPromptTitle": "Prompt"
    },
    "laydate": {
        "months": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        "weeks": ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        "time": ["Hour", "Minute", "Second"],
        "literal": {
            "year": "Year"
        },
        "selectDate": "Select date",
        "selectTime": "Select time",
        "startTime": "Start time",
        "endTime": "End time",
        "tools": {
            "confirm": "Confirm",
            "clear": "Clear",
            "now": "Now",
            "reset": "Reset"
        },
        "rangeOrderPrompt": "End time cannot be earlier than start time\nPlease reselect",
        "invalidDatePrompt": "Not within valid date or time range\n",
        "formatErrorPrompt": "Invalid date format\nMust follow:\n{format}\n",
        "autoResetPrompt": "Auto reset",
        "preview": "Currently selected result"
    },
    "layer": {
        "confirm": "Confirm",
        "cancel": "Cancel",
        "defaultTitle": "Information",
        "prompt": {
            "InputLengthPrompt": "Maximum {length} characters allowed"
        },
        "photos": {
            "noData": "No images",
            "tools": {
                "rotate": "Rotate",
                "scaleX": "Flip horizontal",
                "zoomIn": "Zoom in",
                "zoomOut": "Zoom out",
                "reset": "Reset",
                "close": "Close"
            },
            "viewPicture": "View original",
            "urlError": {
                "prompt": "Current image URL is abnormal,\ncontinue to view next?",
                "confirm": "Next",
                "cancel": "Cancel"
            }
        }
    },
    "laypage": {
        "prev": "Previous",
        "next": "Next",
        "first": "First",
        "last": "Last",
        "total": "Total {total} items",
        "pagesize": "Items/page",
        "goto": "Go to",
        "page": "Page",
        "confirm": "Confirm"
    },
    "table": {
        "sort": {
            "asc": "Ascending",
            "desc": "Descending"
        },
        "noData": "No data available",
        "tools": {
            "filter": {
                "title": "Filter columns"
            },
            "export": {
                "title": "Export",
                "noDataPrompt": "No data in current table",
                "compatPrompt": "Export not supported in IE, please use Chrome or other modern browsers",
                "csvText": "Export CSV"
            },
            "print": {
                "title": "Print",
                "noDataPrompt": "No data in current table"
            }
        },
        "dataFormatError": "Returned data does not meet specifications, correct success status code should be: \"{statusName}\": {statusCode}",
        "xhrError": "Request error: {msg}"
    },
    "transfer": {
        "noData": "No data available",
        "noMatch": "No matching data",
        "title": ["List 1", "List 2"],
        "searchPlaceholder": "Keyword search"
    },
    "tree": {
        "defaultNodeName": "Unnamed",
        "noData": "No data available",
        "deleteNodePrompt": "Confirm delete \"{name}\" node?"
    },
    "upload": {
        "fileType": {
            "file": "File",
            "image": "Image",
            "video": "Video",
            "audio": "Audio"
        },
        "validateMessages": {
            "fileExtensionError": "Selected {fileType} contains unsupported formats",
            "filesOverLengthLimit": "Maximum {length} files allowed simultaneously",
            "currentFilesLength": "Currently selected: {length} files",
            "fileOverSizeLimit": "File size cannot exceed {size}"
        },
        "chooseText": "{length} files"
    },
    "util": {
        "timeAgo": {
            "days": "{days} days ago",
            "hours": "{hours} hours ago",
            "minutes": "{minutes} minutes ago",
            "future": "Future",
            "justNow": "Just now"
        },
        "toDateString": {
            "meridiem": function (hours, minutes) {
                var hm = hours * 100 + minutes;
                if (hm < 500) {
                    return 'Early morning';
                } else if (hm < 800) {
                    return 'Morning';
                } else if (hm < 1200) {
                    return 'AM';
                } else if (hm < 1300) {
                    return 'Noon';
                } else if (hm < 1900) {
                    return 'PM';
                }
                return 'Evening';
            }
        }
    }
};
