import { createContext, useState, useMemo, useContext, useEffect } from "react";

const StateContext = createContext({
  user: null,
  setUser: () => {},
  token: null,
  setToken: () => {}
});

export const ContextProvider = ({ children }) => {
  // Load user from localStorage only once
  const [user, setUser] = useState(() => {
    const storedUser = localStorage.getItem("USER");
    return storedUser ? JSON.parse(storedUser) : null;
  });

  // Load token from localStorage only once
  const [token, _setToken] = useState(() =>
    localStorage.getItem("ACCESS-TOKEN") || null
  );

  // Persist token
  const setToken = (tokenValue) => {
    _setToken(tokenValue);
    if (tokenValue) {
      localStorage.setItem("ACCESS-TOKEN", tokenValue);
    } else {
      localStorage.removeItem("ACCESS-TOKEN");
    }
  };

  // Persist user
  useEffect(() => {
    if (user) {
      localStorage.setItem("USER", JSON.stringify(user));
    } else {
      localStorage.removeItem("USER");
    }
  }, [user]);

  // Memoized context (avoids re-renders)
  const contextValue = useMemo(
    () => ({
      user,
      setUser,
      token,
      setToken
    }),
    [user, token]
  );

  return (
    <StateContext.Provider value={contextValue}>
      {children}
    </StateContext.Provider>
  );
};

export const useStateContext = () => useContext(StateContext);
