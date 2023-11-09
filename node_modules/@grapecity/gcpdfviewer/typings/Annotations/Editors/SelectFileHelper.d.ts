import GcPdfViewer from "../../GcPdfViewer";
import { AnnotationBase, AnnotationTypeCode, FileAttachmentAnnotation, StampAnnotation } from "../AnnotationTypes";
export declare class SelectFileHelper {
    static selectFileData(annotationType: AnnotationTypeCode, viewer: GcPdfViewer): Promise<{
        fileId: string;
        fileName: string;
        array: Uint8Array;
    }>;
    static selectFileForAnnotation(annotationId: string, annotationType: AnnotationTypeCode, viewer: GcPdfViewer): Promise<{
        pageIndex: number;
        annotation: AnnotationBase;
    }>;
    private static _findFileName;
    private static createFileInput;
    private static createLayerWithControls;
    static setAnnotationFileData(viewer: GcPdfViewer, pageIndex: number, annotation: FileAttachmentAnnotation | StampAnnotation, array: Uint8Array, fileId?: string, fileName?: string, imageDpi?: number): Promise<{
        pageIndex: number;
        annotation: AnnotationBase;
    }>;
    private static readDataFromFileInput;
}
