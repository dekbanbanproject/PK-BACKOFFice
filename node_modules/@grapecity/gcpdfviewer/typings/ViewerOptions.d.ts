import { WidgetAnnotation, GcProps, LineAnnotation, CircleAnnotation, SquareAnnotation, RedactAnnotation, TextAnnotation, InkAnnotation, PolyLineAnnotation, PolygonAnnotation, FreeTextAnnotation, FileAttachmentAnnotation, SoundAnnotation, ButtonWidget, ChoiceWidget, TextWidget, StampAnnotation, AnnotationBase, SignatureAnnotation, LinkAnnotation, PopupAnnotation } from "./Annotations/AnnotationTypes";
import { ValidationCallerType } from "./FormFiller/types";
import { FieldAppearanceRenderingType, OpenParameters, OptionalContentConfig, StampCategory, ViewerFeatureName } from "./Models/ViewerTypes";
import { LogLevel } from "./SharedDocuments/types";
export declare type ReplyToolSettings = {
    readOnly?: true | false;
    allowAddNote?: true | false;
    allowChangeUserName?: true | false;
    allowAddReply?: true | false;
    allowDelete?: true | false;
    allowStatus?: true | false;
    allowChangeOtherUser?: true | false;
    allowDeleteOtherUser?: true | false;
    allowStatusOtherUser?: true | false;
    allowAddReplyOtherUser?: true | false;
};
export declare type SupportApiSettings = {
    apiUrl: string;
    token?: string;
    requestInit?: RequestInit;
    webSocketUrl?: string | false;
    reconnectInterval?: number;
    suppressInfoMessages?: boolean;
    suppressErrorMessages?: boolean;
    docBaseUrl?: string;
};
export declare class ViewerOptions {
    constructor(options?: Partial<ViewerOptions>);
    editorDefaults: {
        fontNames?: {
            value: string;
            name: string;
        }[];
        hideFloatingBar?: boolean;
        rememberLastValues?: boolean;
        lastValueKeys?: string[];
        resizeHandleSize?: number;
        moveHandleSize?: number;
        dotHandleSize?: number;
        stickyNote: {
            color?: string;
            contents?: string;
        };
        lineAnnotation?: Partial<LineAnnotation>;
        circleAnnotation?: Partial<CircleAnnotation>;
        squareAnnotation?: Partial<SquareAnnotation>;
        linkAnnotation?: Partial<LinkAnnotation>;
        redactAnnotation?: Partial<RedactAnnotation>;
        inkAnnotation?: Partial<InkAnnotation>;
        polyLineAnnotation?: Partial<PolyLineAnnotation>;
        polygonAnnotation?: Partial<PolygonAnnotation>;
        textAnnotation?: Partial<TextAnnotation>;
        freeTextAnnotation?: Partial<FreeTextAnnotation>;
        fileAttachmentAnnotation?: Partial<FileAttachmentAnnotation>;
        soundAnnotation?: Partial<SoundAnnotation>;
        stampAnnotation?: Partial<StampAnnotation>;
        popupAnnotation?: Partial<PopupAnnotation>;
        checkBoxButton?: Partial<ButtonWidget>;
        radioButton?: Partial<ButtonWidget>;
        pushButton?: Partial<ButtonWidget>;
        resetButton?: Partial<ButtonWidget>;
        submitButton?: Partial<ButtonWidget>;
        comboChoice?: Partial<ChoiceWidget>;
        listBoxChoice?: Partial<ChoiceWidget>;
        passwordField?: Partial<TextWidget>;
        textArea?: Partial<TextWidget>;
        textField?: Partial<TextWidget>;
        signatureField?: Partial<SignatureAnnotation>;
        combTextField?: Partial<TextWidget>;
    };
    allowedTools: {
        viewer?: 'all' | 'annotations' | 'fields' | string[];
        annotationEditor?: 'all' | 'annotations' | 'fields' | string[];
        formEditor?: 'all' | 'annotations' | 'fields' | string[];
    };
    fieldsAppearance: {
        radioButton?: FieldAppearanceRenderingType;
        checkBoxButton?: FieldAppearanceRenderingType;
        pushButton?: FieldAppearanceRenderingType;
    };
    hideAnnotationPopups: true | false;
    lockOpenTimeout: number;
    snapAlignment: true | false | {
        tolerance: number | {
            horizontal: number | false;
            vertical: number | false;
        };
        margin: false | true | number | {
            horizontal: number | boolean;
            vertical: number | boolean;
        };
        center: false | true | {
            horizontal: boolean;
            vertical: boolean;
        };
    };
    useNativeContextMenu: boolean;
    onBeforeOpenContextMenu?: Function;
    onBeforeCloseContextMenu?: Function;
    optionalContentConfig?: OptionalContentConfig;
    replyTool?: ReplyToolSettings;
    userName?: string;
    baseUrl?: string;
    buttons?: string[] | "all" | 'none';
    caret?: true | false | {
        caretBlinkTime: number;
        caretStopBlinkTime: number;
        color: string;
        width: number;
        allowForPan: boolean;
    };
    sharing?: {
        disallowUnknownUsers?: boolean;
        knownUserNames?: string[];
        presenceColors?: {
            [userName: string]: string;
        };
        presenceMode: 'on' | 'others' | 'off' | true | false;
    };
    coordinatesPrecision: number;
    coordinatesOrigin?: 'TopLeft' | 'BottomLeft';
    disableFeatures?: ViewerFeatureName[];
    disablePageLabels?: boolean;
    externalLinkTarget?: 'blank' | 'self' | 'parent' | 'top' | 'none';
    formFiller?: FormFillerSettings;
    enableXfa?: boolean;
    secondToolbar?: {
        render?: (toolbarKey: string) => any[] | null;
    };
    signTool?: SignToolSettings;
    language?: 'en' | string;
    maxCanvasPixels?: number;
    userData: any;
    useCanvasForSelection: any;
    renderInteractiveForms: boolean;
    keepFileData: boolean;
    renderer: 'canvas' | 'svg';
    friendlyFileName?: string;
    file?: string | any;
    onInitialized?: (viewer: any) => void;
    openParameters?: OpenParameters;
    password: string;
    restoreViewStateOnLoad?: false | true | {
        trackViewMode?: boolean;
        trackMouseMode?: boolean;
        trackScale?: boolean;
        trackPageRotation?: boolean;
        trackFullScreen?: boolean;
        trackTheme?: boolean;
        trackSidebar?: boolean;
        trackSidebarWidth?: boolean;
        trackFile?: boolean;
    };
    documentListUrl: string;
    workerSrc: string;
    zoomByMouseWheel: {
        always: boolean;
        ctrlKey: boolean;
        metaKey: boolean;
    };
    theme: string | false;
    themes: string[];
    requireTheme?: "light" | "dark" | "viewer" | "light-blue" | "themes/dark-yellow" | "gc-blue" | null;
    hideAnnotationTypes?: ('Text' | 'Link' | 'FreeText' | 'Line' | 'Square' | 'Circle' | 'Polygon' | 'Polyline' | 'Ink' | 'Popup' | 'FileAttachment' | 'Sound' | 'ThreadBead' | 'RadioButton' | 'Checkbox' | 'PushButton' | 'Choice' | 'TextWidget' | 'Redact' | 'Signature' | 'Stamp')[] | 'All' | 'None';
    cMapUrl?: string;
    cMapPacked?: boolean;
    stamp: {
        dpi?: number;
        stampCategories?: StampCategory[] | boolean;
    };
    supportApi?: string | SupportApiSettings;
    logLevel?: LogLevel;
    shortcuts: any;
}
export declare type RulerLine = {
    color?: string;
    position?: number;
    size?: number;
    type?: 'solid' | 'dashed';
};
export declare type FormFieldMapping = {
    hidden?: boolean;
    nolabel?: boolean;
    orderindex?: number;
    rowcustomcss?: string;
    validator?: (fieldValue: string | string[], field: WidgetAnnotation, args: {
        caller: ValidationCallerType;
    }) => boolean | string;
} & GcProps;
export declare type SignToolSettings = {
    dialogLocation?: 'Center' | 'Top' | 'Right' | 'Bottom' | 'Left' | {
        x: number;
        y: number;
    };
    hideTabs?: boolean;
    hideToolbar?: boolean;
    hideDialogTitle?: boolean;
    hideSaveSignature?: boolean;
    hideDialogFooter?: boolean;
    tabs?: ('Type' | 'Draw' | 'Image')[];
    selectedTab?: 'Type' | 'Draw' | 'Image';
    title?: string;
    penColor?: string;
    penWidth?: number;
    text?: string;
    textColor?: string;
    fontSize?: number;
    fontName?: string;
    fontNames?: string[];
    italic?: boolean;
    bold?: boolean;
    hasImage?: boolean;
    saveSignature?: boolean;
    annotationType?: 'stamp' | 'signature';
    convertToContent?: boolean;
    subject?: string;
    pageIndex?: number;
    canvasSize?: {
        width: number;
        height: number;
    };
    autoResizeCanvas?: boolean;
    location?: 'Center' | 'Top' | 'Right' | 'Bottom' | 'Left' | 'TopLeft' | 'TopRight' | 'BottomRight' | 'BottomLeft' | {
        x: number;
        y: number;
    };
    destinationScale?: number;
    ruler?: {
        Draw: RulerLine[] | false;
        Type: RulerLine[] | false;
        Image: RulerLine[] | false;
    } | false;
    beforeShow?: (signatureDialog: any) => boolean;
    afterShow?: (signatureDialog: any) => void;
    beforeAdd?: (activeTool: any, signatureDialog: any) => boolean;
    afterAdd?: (result: {
        pageIndex: number;
        annotation: AnnotationBase;
    }) => void;
    beforeHide?: (signatureDialog: any) => boolean;
};
export declare type FormFillerSettings = {
    applyAfterFailedValidation?: 'confirm' | 'reject' | 'apply' | Function;
    layout?: 'Auto' | 'OneColumn' | 'TwoColumns';
    title?: string;
    validator?: (fieldValue: string | string[], field: WidgetAnnotation, args: {
        caller: ValidationCallerType;
    }) => boolean | string;
    onInitialize?: (formFiller: any) => void;
    beforeApplyChanges?: (formFiller: any) => boolean;
    beforeFieldChange?: (field: WidgetAnnotation, formFiller: any) => boolean;
    mappings: {
        [fieldName: string]: FormFieldMapping;
    };
};
