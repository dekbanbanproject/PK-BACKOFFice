import { FileAttachmentAnnotation } from "../Annotations/AnnotationTypes";
export declare type AttachmentsModel = {
    attachments: FileAttachmentAnnotation[] | null;
};
export declare type AttachmentsMsg = {
    type: 'reset';
};
