import { EditMode } from "./types";
export declare type LinkType = 'url' | 'action' | 'dest' | 'js' | 'unknown';
export declare type LinkDestinationType = 'XYZ' | 'Fit' | 'FitH' | 'FitBH' | 'FitV' | 'FitBV' | 'FitR' | 'FitB';
export declare type NamedAction = 'GoBack' | 'GoForward' | 'NextPage' | 'PrevPage' | 'LastPage' | 'FirstPage';
export declare type GcProps = {
    autocomplete?: string | 'on' | 'off' | 'name' | 'honorific-prefix' | 'given-name' | 'additional-name' | 'family-name' | 'honorific-suffix' | 'nickname' | 'email' | 'username' | 'new-password' | 'current-password' | 'one-time-code' | 'organization-title' | 'organization' | 'street-address' | 'address-line1' | 'address-line2' | 'address-line3' | 'address-level4' | 'address-level3' | 'address-level2' | 'address-level1' | 'country' | 'country-name' | 'postal-code' | 'cc-name' | 'cc-given-name' | 'cc-additional-name' | 'cc-family-name' | 'cc-number' | 'cc-exp' | 'cc-exp-month' | 'cc-exp-year' | 'cc-csc' | 'cc-type' | 'transaction-currency' | 'transaction-amount' | 'language' | 'bday' | 'bday-day' | 'bday-month' | 'bday-year' | 'sex' | 'tel' | 'tel-country-code' | 'tel-national' | 'tel-area-code' | 'tel-local' | 'tel-local-prefix' | 'tel-local-suffix' | 'tel-extension' | 'impp' | 'url' | 'photo';
    autofocus?: boolean;
    defaultvalue?: string;
    disabled?: boolean;
    inputmode?: 'numeric' | 'string';
    displayname?: string;
    min?: any;
    max?: any;
    maxlength?: number;
    minlength?: number;
    multiline?: boolean;
    multiple?: boolean;
    orderindex?: number;
    pattern?: string;
    placeholder?: string;
    readonly?: boolean;
    required?: boolean;
    spellcheck?: 'true' | 'false';
    title?: string;
    validationmessage?: string;
    validateoninput?: boolean;
    type?: 'text' | 'date' | 'time' | 'month' | 'week' | 'number' | 'tel' | 'search' | 'textarea' | string;
};
export declare type CopyBufferData = {
    type: 'annotation' | 'empty';
    data?: {
        pageIndex: number;
        annotation: AnnotationBase;
    };
};
export declare type AnnotationStateModel = 'Marked' | 'Review';
export declare type AnnotationMarkedStateType = 'Marked' | 'Unmarked';
export declare type AnnotationReviewStateType = 'None' | 'Accepted' | 'Cancelled' | 'Completed' | 'Rejected';
export declare type AnnotationTypeName = ('Text' | 'Link' | 'FreeText' | 'Line' | 'Square' | 'Circle' | 'Polygon' | 'PolyLine' | 'Highlight' | 'Underline' | 'Squiggly' | 'Strikeout' | 'Stamp' | 'Caret' | 'Ink' | 'Popup' | 'FileAttachment' | 'Sound' | 'Movie' | 'Widget' | 'Screen' | 'PrinterMark' | 'TrapNet' | 'WaterMark' | 'Redact' | 'Signature' | 'ThreadBead' | 'RadioButton' | 'Checkbox' | 'PushButton' | 'Choice' | 'TextWidget' | 'Redact');
export declare type WidgetFieldTypeName = ('Tx' | 'Btn' | 'Ch');
export declare type LineEndStyle = ("Square" | "Circle" | "Diamond" | "OpenArrow" | "ClosedArrow" | "None" | "Butt" | "ROpenArrow" | "RClosedArrow" | "Slash");
export declare enum AnnotationTypeCode {
    TEXT = 1,
    LINK = 2,
    FREETEXT = 3,
    LINE = 4,
    SQUARE = 5,
    CIRCLE = 6,
    POLYGON = 7,
    POLYLINE = 8,
    HIGHLIGHT = 9,
    UNDERLINE = 10,
    SQUIGGLY = 11,
    STRIKEOUT = 12,
    STAMP = 13,
    CARET = 14,
    INK = 15,
    POPUP = 16,
    FILEATTACHMENT = 17,
    SOUND = 18,
    MOVIE = 19,
    WIDGET = 20,
    SCREEN = 21,
    PRINTERMARK = 22,
    TRAPNET = 23,
    WATERMARK = 24,
    THREED = 25,
    REDACT = 26,
    SIGNATURE = 27,
    THREADBEAD = 150
}
export declare enum TextAlignmentType {
    Left = 0,
    Center = 1,
    Right = 2
}
export declare function isStatusTextAnnotation(item: TextAnnotation): boolean;
export declare function isFormFieldWidget(item: AnnotationBase): boolean;
export declare function isRadioButtonFieldFidget(item: AnnotationBase): boolean;
export declare function findFieldObjectType(node: WidgetAnnotation): string;
export declare function findAnnotationSubType(annotationType: AnnotationTypeCode, defaultValue: string | AnnotationTypeName): AnnotationTypeName;
export declare function getAnnotationOptionsKeyName(annotation: AnnotationBase): string;
export declare type ToolbarButtonKey = 'edit-select' | 'share' | 'edit-text' | 'edit-free-text' | 'edit-ink' | 'edit-square' | 'edit-circle' | 'edit-line' | 'edit-polyline' | 'edit-polygon' | 'edit-stamp' | 'edit-file-attachment' | 'edit-sound' | 'edit-link' | 'edit-redact' | 'edit-redact-apply' | 'edit-erase' | 'edit-undo' | 'edit-redo' | 'new-document' | 'new-page' | 'delete-page' | 'edit-select-field' | 'share' | 'edit-widget-tx-field' | 'edit-widget-tx-password' | 'edit-widget-tx-text-area' | 'edit-widget-btn-checkbox' | 'edit-widget-btn-radio' | 'edit-widget-btn-push' | 'edit-widget-ch-combo' | 'edit-widget-ch-list-box' | 'edit-widget-tx-comb' | 'edit-widget-btn-submit' | 'edit-widget-btn-reset' | 'edit-erase-field' | 'edit-undo' | 'edit-redo' | 'new-document' | 'new-page' | 'delete-page' | 'edit-sign-tool' | 'open' | 'download' | 'save' | 'text-selection' | 'pan' | 'text-tools' | 'draw-tools' | 'attachment-tools' | 'form-tools' | 'page-tools' | 'rotate' | 'view-mode' | 'theme-change' | 'print' | 'hide-annotations' | 'form-filler' | 'doc-title' | 'doc-properties' | 'about' | '$split' | 'zoom' | '$zoom' | '$navigation' | '$fullscreen';
export declare function edtorModeToButtonKey(editorMode: EditMode): ToolbarButtonKey;
export declare class AnnotationBase {
    sharedChanges?: {
        [userName: string]: number;
    };
    id: string;
    annotationName: string;
    convertToContent?: boolean;
    display?: 'visible' | 'hidden';
    orderIndex: number;
    annotationFlags: number;
    annotationType: AnnotationTypeCode;
    borderStyle?: {
        width: number;
        style: number;
        dashArray?: number[];
        horizontalCornerRadius: number;
        verticalCornerRadius: number;
    };
    appearanceColor?: string;
    color?: string;
    contents?: string;
    noRotateFlag?: boolean;
    opacity?: number;
    textAlignment: TextAlignmentType;
    isRichContents: boolean;
    subtype: AnnotationTypeName;
    title: string;
    subject: string;
    rect: number[];
    appearanceBBox: number[];
    useCustomAppearance: boolean;
    printableFlag: boolean;
    creationDate: string;
    modificationDate: string;
    popupId?: string;
    parentId?: string;
    parentAnnotation?: AnnotationBase;
    referenceType: 'R' | 'Group';
    referenceAnnotationId: string;
    rotate?: number;
    rotationInit?: {
        initRect: number[];
        rotatedRect?: number[];
        transformedRect?: number[];
        angle: number;
        dx?: number;
        dy?: number;
    };
    irtAnnotations?: AnnotationBase[];
    state?: AnnotationMarkedStateType | AnnotationReviewStateType;
    stateModel?: AnnotationStateModel;
    invisibleFlag: boolean;
    locked: boolean;
    __stateVersion: number;
    gcProps?: GcProps;
    constructor();
}
export declare class PopupAnnotation extends AnnotationBase {
    open: boolean;
    constructor();
}
export declare class MarkupAnnotation extends AnnotationBase {
    hasPopup: boolean;
    popupId: string;
    constructor();
}
export declare class LineAnnotation extends MarkupAnnotation {
    lineCoordinates: number[];
    interiorColor?: string;
    lineStart: LineEndStyle;
    lineEnd: LineEndStyle;
    constructor();
}
export declare class PolyLineAnnotation extends MarkupAnnotation {
    vertices: {
        x: number;
        y: number;
    }[];
    interiorColor?: string;
    constructor();
}
export declare class PolygonAnnotation extends PolyLineAnnotation {
    constructor();
}
export declare class CircleAnnotation extends MarkupAnnotation {
    cx: number;
    cy: number;
    rx: number;
    ry: number;
    interiorColor?: string;
    constructor();
}
export declare class SquareAnnotation extends MarkupAnnotation {
    interiorColor?: string;
    constructor();
}
export declare class RedactAnnotation extends MarkupAnnotation {
    markBorderColor?: string;
    markFillColor?: string;
    overlayFillColor?: string;
    overlayText?: string;
    overlayTextJustification?: TextAlignmentType;
    redactApplied: boolean;
    constructor();
}
export declare class InkAnnotation extends MarkupAnnotation {
    inkLists: {
        x: number;
        y: number;
    }[][];
    constructor();
}
export declare class LinkAnnotation extends MarkupAnnotation {
    linkType?: LinkType;
    url: string;
    newWindow: boolean;
    dest: (object | number | null)[];
    destType?: LinkDestinationType;
    destX: number;
    destY: number;
    destW: number;
    destH: number;
    destScale: number | 'page-fit' | 'page-width' | 'page-height';
    destPageNumber?: number;
    action: NamedAction;
    jsAction: string;
    constructor();
}
export declare class TextAnnotation extends MarkupAnnotation {
    static DEFAULT_WIDTH: number;
    static DEFAULT_HEIGHT: number;
    open: boolean;
    name: 'Comment' | 'Key' | 'Note' | 'Help' | 'NewParagraph' | 'Paragraph' | 'Insert';
    state?: AnnotationMarkedStateType | AnnotationReviewStateType;
    stateModel?: AnnotationStateModel;
    displayState: AnnotationMarkedStateType | AnnotationReviewStateType;
    isRichContents: boolean;
    constructor();
}
export declare class FreeTextAnnotation extends MarkupAnnotation {
    calloutLine: number[];
    calloutLineEnd: LineEndStyle;
    isRichContents: boolean;
    fontSize: number;
    fontName: string;
    constructor();
}
export declare class SignatureAnnotation extends MarkupAnnotation {
    fileId?: string;
    fileIdChanged?: boolean;
    fileName?: string;
    constructor();
}
export declare class StampAnnotation extends MarkupAnnotation {
    fileId?: string;
    fileIdChanged?: boolean;
    fileName?: string;
    constructor();
}
export declare class FileAttachmentAnnotation extends MarkupAnnotation {
    name: 'Graph' | 'PushPin' | 'Paperclip' | 'Tag';
    fileName?: string;
    fileId?: string;
    fileIdChanged?: boolean;
    fileLength?: number;
    isNew?: boolean;
    file: any;
    constructor();
}
export declare class SoundAnnotation extends MarkupAnnotation {
    name: 'Speaker' | 'Mic';
    soundBytes: Uint8Array;
    audioProperties: {
        numChannels: 1 | 2 | number;
        sampleRate: number;
        bytesPerSample: 2 | 8 | 32 | 128 | 256 | number;
        subchunk2Size: 538100 | number;
    };
    playerOptions: {
        encoding: "16bitInt";
        channels: 1 | 2 | number;
        sampleRate: 44100 | number;
    };
    fileName?: string;
    fileId?: string;
    fileIdChanged?: boolean;
    constructor();
}
export declare class WidgetAnnotation extends AnnotationBase {
    fieldType: WidgetFieldTypeName;
    fieldName: string;
    fieldValue: string | string[];
    fontSize: number;
    fontName: string;
    orientation?: number;
    readOnly: boolean;
    constructor();
}
export declare class TextWidget extends WidgetAnnotation {
    multiLine: boolean;
    comb: boolean;
    maxLen: number;
    hasPasswordFlag: boolean;
    backgroundColor: string;
    borderColor: string;
    required: boolean;
    constructor();
}
export declare class ButtonWidget extends WidgetAnnotation {
    checkBox: boolean;
    radioButton: boolean;
    pushButton: boolean;
    submitForm: boolean;
    resetForm: boolean;
    buttonValue: string;
    radiosInUnison: boolean;
    constructor();
}
export declare class ChoiceWidget extends WidgetAnnotation {
    combo: boolean;
    multiSelect: boolean;
    options: {
        displayValue: string;
        exportValue: string;
    }[];
    hasEditFlag: boolean;
    constructor();
}
