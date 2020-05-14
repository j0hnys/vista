import HelloType, { Dict, Enum, Tuple, List, Type, Rule, Self, IfExists } from 'hello-type'

const Index = new Type({
    variable: String,
});

export default {
    namespace: 'models/Types/Index',
    assert(data) {
        Index.assert(data);
    },
};