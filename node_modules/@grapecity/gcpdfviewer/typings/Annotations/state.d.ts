import { AnnotationsMsg, AnnotationsModel } from './types';
export declare const init: () => AnnotationsModel;
declare const reducer: (message: AnnotationsMsg, model: AnnotationsModel) => AnnotationsModel;
export { reducer as update };
