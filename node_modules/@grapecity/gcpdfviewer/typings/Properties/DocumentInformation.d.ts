export declare type DocumentInformation = {
    Author?: string;
    FileName: string;
    FileSizeBytes: number;
    CreationDate?: string;
    Creator?: string;
    IsAcroFormPresent?: boolean;
    IsCollectionPresent?: boolean;
    IsLinearized?: boolean;
    IsXFAPresent?: boolean;
    Keywords: string;
    ModDate?: string;
    PDFFormatVersion?: string;
    Producer?: string;
    Subject?: string;
    Title?: string;
    PagesCount?: number;
    PageSizeInches?: {
        width: number;
        height: number;
    };
};
