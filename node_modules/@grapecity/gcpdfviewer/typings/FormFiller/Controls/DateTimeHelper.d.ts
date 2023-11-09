export declare class DateTimeHelper {
    inputValue: string;
    type?: string | undefined;
    isEmpty: boolean;
    isValid: boolean;
    private hasTime;
    private timeOnly;
    constructor(inputValue: string, type?: string | undefined);
    get pickerMode(): 'date' | 'date-time' | undefined;
    get placeholder(): string;
    get dateFormat(): string | undefined;
    get timeFormat(): string | undefined;
    setValue(changedValue: string | any): void;
    get valueFormat(): string;
    private setValueInternal;
    get fieldValue(): string;
    get displayValue(): string;
}
