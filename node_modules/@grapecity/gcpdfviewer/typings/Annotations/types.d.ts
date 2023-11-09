import { AnnotationBase } from "./AnnotationTypes";
export declare type SetMultiplePropertiesFn<Val = any> = (pageIndex: number, originalNode: AnnotationBase, descriptors: PropertyDescriptor[], values: Val[]) => Promise<any>;
export declare type AnnotationsMsg = {
    type: 'reset';
};
export declare type AnnotationsModel = {
    layoutMode: LayoutMode;
    expandedPageIndex: number;
    expandedAnnotationIds: any;
    selectedAnnotationId: string;
    data: {
        pageIndex: number;
        annotations: AnnotationBase[];
    }[] | null;
};
export declare enum LayoutMode {
    Viewer = 0,
    AnnotationEditor = 1,
    FormEditor = 2
}
export declare enum EditMode {
    None = 0,
    Text = 1,
    FreeText = 2,
    Ink = 3,
    Square = 4,
    Erase = 5,
    Line = 6,
    Circle = 7,
    Polyline = 8,
    Polygon = 9,
    FileAttachment = 10,
    Sound = 11,
    Highlight = 12,
    Underline = 13,
    Squiggly = 14,
    StrikeOut = 15,
    Stamp = 16,
    Redact = 17,
    Select = 18,
    TextFieldWidget = 19,
    CombTextFieldWidget = 20,
    TextAreaWidget = 21,
    PasswordFieldWidget = 22,
    CheckBoxButtonWidget = 23,
    RadioButtonWidget = 24,
    PushButtonWidget = 25,
    ResetButtonWidget = 26,
    SubmitButtonWidget = 27,
    ComboChoiceWidget = 28,
    ListBoxChoiceWidget = 29,
    Link = 30
}
