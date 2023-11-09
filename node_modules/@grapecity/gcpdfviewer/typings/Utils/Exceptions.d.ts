export declare type GcPdfViewerException = {
    message: string;
    name: string | 'PasswordException' | 'UnknownErrorException' | 'InvalidPDFException' | 'MissingPDFException' | 'UnexpectedResponseException' | 'FormatError' | 'AbortException';
};
export declare function ensureException(ex: string | GcPdfViewerException, in17n?: any): GcPdfViewerException;
