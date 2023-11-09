import { UserAccess, SharedAccessMode, SharedDocumentInfo, ModificationType, ModificationsState } from "../SharedDocuments/types";
import { AnnotationBase } from "../Annotations/AnnotationTypes";
import { ClientRequestType, ClientMessage, ClientMessageType } from "./Connection/ClientMessage";
import { ServerMessage, StartSharedModeResponse } from "./Connection/ServerMessage";
import { LocalDocumentModification } from "./LocalDocumentModification";
import { ProgressDialogSink } from "../Dialogs/Types";
import { StampCategory } from "../Models/ViewerTypes";
export declare class DocumentInfo {
    title: string;
    creator: string;
    producer: string;
    author: string;
    subject: string;
    keywords: string;
    creationDate?: string;
    modifyDate?: string;
}
export declare class OpenDocumentInfo {
    accessMode: SharedAccessMode;
    documentId: string;
    fileName: string;
    pagesCount: number;
    defaultViewPortSize: {
        w: number;
        h: number;
    };
    info: DocumentInfo;
}
export declare type DocumentModification = {
    renderInteractiveForms?: boolean;
    rotation?: number;
    formData?: any;
    annotationsData?: {
        newAnnotations: any[];
        updatedAnnotations: any[];
        removedAnnotations: any[];
    };
};
export declare type SignatureInfo = {
    ContactInfo: string;
    Location: string;
    SignerName: string;
    Reason: string;
    SignatureDigestAlgorithm: 'SHA1' | 'SHA256' | 'SHA384' | 'SHA512' | 'PKCS7SHA1';
    SignatureFormat: 'PKCS7Detached' | 'PKCS7SHA1';
    TimeStamp: {
        ServerUrl?: string;
        UserName?: string;
        Password?: string;
    };
    SignatureField: string;
};
export interface ISupportApi {
    status: 'opening' | 'opening-shared' | 'opened-shared' | 'opened' | 'closed';
    clientId: string;
    docInfo: OpenDocumentInfo;
    documentId: string;
    isDocumentShared: boolean;
    isConnected: boolean;
    connect(lazy: boolean): Promise<boolean>;
    hasPersistentConnection: boolean;
    sharedAccessMode: SharedAccessMode;
    userAcesssList: UserAccess[];
    applyOptions(options: any): any;
    getLastError(): Promise<string>;
    canEditAnnotation(annotation?: AnnotationBase | null): boolean;
    close(): Promise<string>;
    dispose(): any;
    listAllUsers(): Promise<string[]>;
    listUsersWithAccess(): Promise<UserAccess[]>;
    listSharedDocuments(): Promise<SharedDocumentInfo[]>;
    modifySharedDocument(type: ModificationType, data?: {
        pageIndex: number;
        annotation: AnnotationBase;
    } | {
        pageIndex: number;
        annotationId: string;
    } | {
        resultStructure: number[];
        structureChanges: {
            pageIndex: number;
            add: boolean;
            checkNumPages: number;
        }[];
        pdfInfo: {
            numPages: number;
            fingerprint: string;
        };
    }): any;
    openSharedDocument(documentId: string): Promise<OpenDocumentInfo>;
    shareDocument(userName: string, accessMode: SharedAccessMode, modificationsState: ModificationsState, startSharedMode: boolean): Promise<OpenDocumentInfo | null>;
    startSharedMode(): Promise<StartSharedModeResponse>;
    stopSharedMode(): Promise<void>;
    onPushMessage(message: ServerMessage): any;
    unshareDocument(userName: string): Promise<void>;
    sendMessage(type: ClientMessageType, messageData: Partial<ClientMessage>): any;
    sendRequest<T>(type: ClientRequestType, messageData: Partial<ClientMessage>): Promise<T>;
    setOptions(): Promise<string>;
    openBinary(data: any): Promise<OpenDocumentInfo>;
    getDownloadUrl(filename: string): string;
    getDownloadUnmodifiedUrl(filename: string): string;
    checkDocumentLoader(): Promise<boolean>;
    collectModifiedFiles(documentModification: LocalDocumentModification | ModificationsState): string[];
    uploadFiles(fileIds: string[], sink?: ProgressDialogSink): Promise<boolean>;
    downloadFiles(fileIds: string[], sink?: ProgressDialogSink): Promise<boolean>;
    modify(documentModification: DocumentModification): Promise<string>;
    sign(signatureInfo: SignatureInfo): Promise<boolean>;
    verifySignature(fieldName: string): Promise<boolean>;
    serverVersion(): Promise<string>;
    getStampCategories(): Promise<StampCategory[]>;
    getStampImageUrl(categoryId: string, imageName: string, enableCache: boolean): string;
}
