export declare type DocumentSecuritySummary = {
    hasSecurity: boolean;
    securityMethod: string;
    encryptionLevel: string;
    documentOpenPassword: boolean;
    permissionsPassword: boolean;
    permissionFlags: number[] | null;
    printing: boolean;
    documentAssembly: boolean;
    contentCopying: boolean;
    contentCopyingForAccessibility: boolean;
    pageExtraction: boolean;
    commenting: boolean;
    fillingOfFormFields: boolean;
    signing: boolean;
    creationOfTemplatePages: boolean;
    modifyContents: boolean;
    error?: string;
};
