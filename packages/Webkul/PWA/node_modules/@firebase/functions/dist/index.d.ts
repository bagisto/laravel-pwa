import { _FirebaseNamespace } from '@firebase/app-types/private';
import * as types from '@firebase/functions-types';
export declare function registerFunctions(instance: _FirebaseNamespace): void;
declare module '@firebase/app-types' {
    interface FirebaseNamespace {
        functions?: {
            (app?: FirebaseApp): types.FirebaseFunctions;
            Functions: typeof types.FirebaseFunctions;
        };
    }
    interface FirebaseApp {
        functions?(region?: string): types.FirebaseFunctions;
    }
}
